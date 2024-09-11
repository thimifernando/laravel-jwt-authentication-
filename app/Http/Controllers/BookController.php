<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all()); 
        // This will dump the incoming request data and stop execution.

        // Creating a new book entry
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->year = $request->year;
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->save();

        // Returning the created book as a JSON response
        return response()->json([
            'message' => 'Book successfully created!',
            'book' => $book
        ], 201);
    }
    public function update(Request $request, $id)
{
    // Find the book by its id
    $book = Book::find($id);

    if (!$book) {
        return response()->json([
            'message' => 'Book not found'
        ], 404);
    }

    // Update the book's details
    $book->title = $request->title;
    $book->author = $request->author;
    $book->year = $request->year;
    $book->isbn = $request->isbn;
    $book->description = $request->description;
    $book->save();

    // Return a success message with the updated book details
    return response()->json([
        'message' => 'Book successfully updated!',
        'book' => $book
    ], 200);
}
public function destroy($id)
{
    // Find the book by its id
    $book = Book::find($id);

    if (!$book) {
        return response()->json([
            'message' => 'Book not found'
        ], 404);
    }

    // Delete the book
    $book->delete();

    // Return a success message
    return response()->json([
        'message' => 'Book successfully deleted!'
    ], 200);
}

}
