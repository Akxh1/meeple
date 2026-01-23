<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentNotification;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current user (student)
     */
    public function getMyNotifications()
    {
        $user = Auth::user();
        
        // Find the student associated with this user
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json([]);
        }
        
        $notifications = StudentNotification::where('student_id', $student->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'icon' => $n->type_icon,
                    'color' => $n->type_color,
                    'title' => $n->title,
                    'message' => $n->message,
                    'sender' => $n->sender?->name ?? 'System',
                    'is_read' => $n->is_read,
                    'time' => $n->created_at->diffForHumans(),
                    'created_at' => $n->created_at->toIso8601String(),
                ];
            });
        
        return response()->json($notifications);
    }
    
    /**
     * Get unread count for badge
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['count' => 0]);
        }
        
        $count = StudentNotification::where('student_id', $student->id)
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead(StudentNotification $notification)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        // Ensure the notification belongs to this student
        if (!$student || $notification->student_id !== $student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        
        StudentNotification::where('student_id', $student->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Send a warning notification to a student (for instructors)
     */
    public function sendWarning(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:info,warning,at_risk,success',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'send_email' => 'boolean',
        ]);
        
        $student = Student::with('user')->findOrFail($request->student_id);
        
        // Create the notification
        $notification = StudentNotification::create([
            'student_id' => $student->id,
            'sender_id' => Auth::id(),
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'is_read' => false,
            'email_sent' => false,
        ]);
        
        // Send email if requested and student has email
        if ($request->send_email && $student->user?->email) {
            try {
                Mail::raw(
                    $request->message,
                    function ($mail) use ($student, $request) {
                        $mail->to($student->user->email)
                             ->subject("{$request->title}")
                             ->from(config('mail.from.address', 'noreply@meeple.test'), 'Meeple Learning System');
                    }
                );
                $notification->update(['email_sent' => true]);
            } catch (\Exception $e) {
                // Email failed but notification still created
            }
        }
        
        return response()->json([
            'success' => true,
            'notification' => [
                'id' => $notification->id,
                'title' => $notification->title,
                'type' => $notification->type,
                'email_sent' => $notification->email_sent,
            ],
        ]);
    }
    
    /**
     * Get all students for the dropdown (instructors only)
     */
    public function getStudentsForDropdown()
    {
        $students = Student::with('user')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'email' => $s->user?->email,
                    'student_id' => $s->student_id,
                ];
            });
        
        return response()->json($students);
    }
}
