<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\AIUsageLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AiController extends Controller
{
    /**
     * Helper to verify if user can use AI features
     */
    private function checkAiQuota($user)
    {
        if ($user->subscription_tier === 'premium' || $user->isAdmin()) {
            return true;
        }

        // Free tier logic: 1 query per day
        $todayUsage = AIUsageLog::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();
            
        return $todayUsage < 1;
    }

    /**
     * General AI Chat
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();

        if (!$this->checkAiQuota($user)) {
            return response()->json([
                'error' => 'Daily AI limit reached. Please upgrade to Premium for unlimited access.'
            ], 403);
        }

        $message = $request->input('message');
        
        // Mock AI response (in production, call actual LLM API)
        $responses = [
            "I can help you understand your study materials better. What specific topic would you like to explore?",
            "Based on your question, I recommend reviewing the key concepts in your course materials. Would you like me to summarize a specific document?",
            "Great question! Let me break this down for you. The main points to focus on are...",
            "I'm analyzing your study patterns. Consider reviewing the materials from your recent courses for better retention.",
            "To improve your understanding, I suggest creating summary notes and practice questions from your materials."
        ];
        
        $response = $responses[array_rand($responses)];

        // Log usage
        AIUsageLog::create([
            'user_id' => $user->id,
            'file_id' => null,
            'query_type' => 'chat',
            'tokens_used' => rand(50, 200),
        ]);

        return response()->json([
            'response' => $response,
            'premium' => $user->subscription_tier === 'premium'
        ]);
    }

    /**
     * Generate a summary for a material
     */
    public function summarize(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $user = Auth::user();

        if (!$this->checkAiQuota($user)) {
            return response()->json([
                'error' => 'Daily AI limit reached. Please upgrade to Premium for unlimited access.'
            ], 403);
        }

        // --- LLM API Call placeholder ---
        // In production, extract text from $material->file_path and send to OpenAI/Claude
        
        $mockSummary = "This document presents a comprehensive overview of " . Str::title($material->course_code) . " topics. " .
            "It emphasizes key concepts critical for academic success in the field. The author discusses historical contexts, " .
            "modern applications, and provides several examples to illustrate the theoretical frameworks discussed. " .
            "Furthermore, it concludes with a set of review questions designed to test the reader's comprehension.";

        // Log usage
        AIUsageLog::create([
            'user_id' => $user->id,
            'file_id' => $material->id,
            'query_type' => 'summary',
            'tokens_used' => rand(150, 450), // Simulated token count
        ]);

        return response()->json([
            'summary' => $mockSummary,
            'premium' => $user->subscription_tier === 'premium'
        ]);
    }

    /**
     * Generate key concepts for a material
     */
    public function keypoints(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $user = Auth::user();

        if (!$this->checkAiQuota($user)) {
            return response()->json([
                'error' => 'Daily AI limit reached. Please upgrade to Premium for unlimited access.'
            ], 403);
        }

        $mockKeypoints = "- Highlights fundamental concepts of " . Str::upper($material->course_code) . ".\n" .
            "- Identifies core methodologies and their practical applications.\n" .
            "- Outlines modern challenges and theoretical frameworks within the domain.\n" .
            "- Provides structured examples and review questions for deeper understanding.";

        // Log usage
        AIUsageLog::create([
            'user_id' => $user->id,
            'file_id' => $material->id,
            'query_type' => 'summary',
            'tokens_used' => rand(100, 300),
        ]);

        return response()->json([
            'keypoints' => $mockKeypoints,
            'premium' => $user->subscription_tier === 'premium'
        ]);
    }

    /**
     * Ask a question about a material
     */
    public function ask(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:500'
        ]);

        $material = Material::findOrFail($id);
        $user = Auth::user();

        if ($user->subscription_tier !== 'premium' && !$user->isAdmin()) {
            return response()->json([
                'error' => 'AI Q&A is a Premium feature. Please upgrade to unlock.'
            ], 403);
        }

        // --- LLM API Call placeholder ---
        // Send document text + user question to OpenAI/Claude

        $question = $request->input('question');
        $mockAnswer = "Based on the material provided, the answer to your question regarding '" . 
            Str::limit($question, 30) . "' is that the text strongly supports the primary hypothesis. " .
            "It states on page 4 that these factors are directly correlated. Ensure you refer back to chapter 2 for more context.";

        // Log usage
        AIUsageLog::create([
            'user_id' => $user->id,
            'file_id' => $material->id,
            'query_type' => 'qna',
            'tokens_used' => rand(250, 800),
        ]);

        return response()->json([
            'answer' => $mockAnswer
        ]);
    }
}
