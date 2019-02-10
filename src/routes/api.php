<?php
Route::apiResource('forums', 'ForumController');
Route::apiResource('forums.topics', 'TopicController');
Route::apiResource('topics.messages', 'MessageController');