<?php

namespace App\Http\Controllers;

use App\Models\MarketingRequest;
use App\Models\RequestComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RequestCommentController extends Controller
{
    public function store(Request $request, MarketingRequest $marketingRequest): RedirectResponse
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        // Only the request owner or users who can view all requests can comment
        $user = auth()->user();
        if ($marketingRequest->user_id !== $user->id && !$user->canViewAllRequests()) {
            abort(403);
        }

        RequestComment::create([
            'marketing_request_id' => $marketingRequest->id,
            'user_id'              => $user->id,
            'body'                 => $request->body,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(RequestComment $comment): RedirectResponse
    {
        $user = auth()->user();
        if ($comment->user_id !== $user->id && !$user->canViewAllRequests()) {
            abort(403);
        }

        $requestId = $comment->marketing_request_id;
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
