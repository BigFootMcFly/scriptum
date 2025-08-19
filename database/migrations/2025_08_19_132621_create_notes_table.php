<?php

use App\Enums\NoteVisibility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create normal Notes table
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->enum('visibility', NoteVisibility::values())
                ->default(NoteVisibility::Private->value)
                ->index();
            $table->string('title')->index();
            $table->string('slug')->index(); // unique per user
            $table->string('body')->comment('tiptap json format');
            // This is the "text" content of the body, without meta data and formatting (like stripping all html tags and more)
            $table->string('body_content')->comment('filtered version of the body');

            $table->softDeletes();

            $table->timestamps();
        });

        // Create the FTS5 table (virtual table)
        DB::statement("
            CREATE VIRTUAL TABLE notes_fts USING fts5(
                title,
                body_content,
                content='notes',
                content_rowid='id'
            )
        ");

        // Triggers to keep FTS in sync
        DB::unprepared("
            CREATE TRIGGER notes_ai AFTER INSERT ON notes BEGIN
                INSERT INTO notes_fts(rowid, title, body_content)
                VALUES (new.id, new.title, new.body_content);
            END;

            CREATE TRIGGER notes_ad AFTER DELETE ON notes BEGIN
                INSERT INTO notes_fts(notes_fts, rowid, title, body_content)
                VALUES('delete', old.id, old.title, old.body_content);
            END;

            CREATE TRIGGER notes_au AFTER UPDATE ON notes BEGIN
                INSERT INTO notes_fts(notes_fts, rowid, title, body_content)
                VALUES('delete', old.id, old.title, old.body_content);
                INSERT INTO notes_fts(rowid, title, body_content)
                VALUES (new.id, new.title, new.body_content);
            END;
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
        Schema::dropIfExists('notes_fts');
    }
};
