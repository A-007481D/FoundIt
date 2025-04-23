<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageReportController extends Controller
{
    public function report(Request $request, $messageId)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $user = Auth::user();
        $message = Message::findOrFail($messageId);
        
        // check if user has already reported this message
        $existingReport = MessageReport::where('message_id', $messageId)
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingReport) {
            return response()->json(['message' => 'You have already reported this message'], 400);
        }
        
        $report = MessageReport::create([
            'message_id' => $messageId,
            'user_id' => $user->id,
            'reason' => $request->reason
        ]);
        
        return response()->json([
            'message' => 'Message reported successfully',
            'report_id' => $report->id
        ]);
    }
}
