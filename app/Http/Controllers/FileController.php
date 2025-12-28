<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileCategory;
use App\Models\Driver;
use App\Models\FileAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function index()
    {
        $files = File::with(['category', 'uploadedBy', 'driver'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $categories = FileCategory::all();
        $drivers = Driver::with('user')->get();
        
        return view('files.index', compact('files', 'categories', 'drivers'));
    }

    public function store(Request $request)
    {
        try {
            // Log incoming request data
            Log::info('File upload request received', [
                'has_file' => $request->hasFile('file'),
                'files' => $request->allFiles(),
                'all_data' => $request->all()
            ]);

            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif|max:10240', // 10MB max
                'category_id' => 'required|exists:file_categories,id|not_in:new', // Make category required and not 'new'
                'new_category' => 'nullable|string|max:100',
                'driver_id' => 'nullable|exists:drivers,id',
                'description' => 'nullable|string|max:1000',
                'tags' => 'nullable|string|max:500',
                'is_public' => 'nullable|boolean'
            ]);

            // Handle new category creation
            $categoryId = $request->category_id;
            if ($request->category_id === 'new' && $request->new_category) {
                $newCategory = FileCategory::create([
                    'category_name' => $request->new_category,
                    'created_by' => Auth::id()
                ]);
                $categoryId = $newCategory->id;
            }

            if (!$request->hasFile('file')) {
                Log::error('No file in request');
                return response()->json([
                    'success' => false,
                    'message' => 'No file was uploaded'
                ], 400);
            }

            $file = $request->file('file');
            Log::info('File details', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType()
            ]);

            $originalName = $file->getClientOriginalName();
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Create storage directory if it doesn't exist
            if (!Storage::disk('local')->exists('files')) {
                Storage::disk('local')->makeDirectory('files');
            }
            
            // Store file in storage/app/files directory
            $filePath = $file->storeAs('files', $filename, 'local');
            
            // Create file record
            $fileRecord = File::create([
                'filename' => $filename,
                'original_filename' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'category_id' => $categoryId,
                'uploaded_by' => Auth::id(),
                'driver_id' => $request->driver_id ?: null,
                'description' => $request->description,
                'tags' => $request->tags,
                'is_public' => $request->has('is_public'),
                'download_count' => 0
            ]);

            Log::info('File uploaded successfully', ['file_id' => $fileRecord->id]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file' => $fileRecord->load(['category', 'uploadedBy', 'driver'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        try {
            $file = File::findOrFail($id);
            
            // Log the access
            FileAccessLog::create([
                'file_id' => $file->id,
                'user_id' => Auth::id(),
                'action' => 'download',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Increment download count
            $file->increment('download_count');
            $file->update(['last_accessed' => now()]);
            
            $filePath = storage_path('app/' . $file->file_path);
            
            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on disk'
                ], 404);
            }
            
            return response()->download($filePath, $file->original_filename);
            
        } catch (\Exception $e) {
            Log::error('File download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to download file'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $file = File::findOrFail($id);
            
            // Log the deletion
            FileAccessLog::create([
                'file_id' => $file->id,
                'user_id' => Auth::id(),
                'action' => 'delete',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Delete physical file
            if (Storage::exists($file->file_path)) {
                Storage::delete($file->file_path);
            }
            
            // Delete database record
            $file->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $file = File::with(['category', 'uploadedBy', 'driver'])->findOrFail($id);
            
            // Log the view
            FileAccessLog::create([
                'file_id' => $file->id,
                'user_id' => Auth::id(),
                'action' => 'view',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            $file->update(['last_accessed' => now()]);
            
            return response()->json([
                'success' => true,
                'file' => $file
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
    }
}