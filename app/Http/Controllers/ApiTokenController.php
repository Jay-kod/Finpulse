<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Store a newly created API token.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token_name' => ['required', 'string', 'max:255'],
        ]);

        $token = $request->user()->createToken($request->token_name);

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'API Token created successfully. Please copy it now, as you will not be able to see it again.',
            'plain_text_token' => $token->plainTextToken,
        ]);
    }

    /**
     * Remove the specified API token.
     */
    public function destroy(Request $request, $tokenId): RedirectResponse
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return back()->with('flash', [
            'type' => 'success',
            'message' => 'API Token revoked successfully.',
        ]);
    }
}
