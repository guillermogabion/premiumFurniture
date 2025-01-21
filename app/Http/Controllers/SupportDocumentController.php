<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportDocument;
use App\Models\GcashQrimage;
use Illuminate\Support\Facades\Storage;

class SupportDocumentController extends Controller
{
    //
    public function uploadDocuments(Request $request)
    {
        if (session()->has('form_submitted')) {
            return response()->json([
                'message' => 'Form already submitted. Please wait.'
            ], 400);
        }

        session()->put('form_submitted', true);

        $validated = $request->validate([
            'documents.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:2048',
            'additional_image' => 'mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $documentPaths = [];

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $documentName = time() . '-' . uniqid() . '.' . $document->extension();

                $document->move(public_path('document'), $documentName);

                $documentPaths[] = $documentName;
            }
        }

        $additionalImagePath = null;
        if ($request->hasFile('additional_image')) {
            $imageName = time() . '-' . uniqid() . '.' . $request->additional_image->extension();

            $request->additional_image->move(public_path('qrcode'), $imageName);

            $additionalImagePath = $imageName;
        }

        $supportDocument = new SupportDocument();
        $supportDocument->user_id = auth()->id();
        $supportDocument->documents = json_encode($documentPaths);
        $supportDocument->save();

        if ($additionalImagePath) {
            $gcashQrImage = new GcashQrimage();
            $gcashQrImage->user_id = auth()->id();
            $gcashQrImage->gcash_qr_code = $additionalImagePath;
            $gcashQrImage->save();
        }

        session()->forget('form_submitted');

        return response()->json([
            'message' => 'Documents and images uploaded successfully!',
            'documents' => $documentPaths,
            'additional_image' => $additionalImagePath
        ]);
    }
}
