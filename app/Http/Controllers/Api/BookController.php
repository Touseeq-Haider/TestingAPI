<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;


class BookController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string',
            'author' => 'required|string',
            'price'  => 'required|numeric',
        ]);

        $book = Book::create($data);

        return new BookResource($book);
    }

    public function show(Book $book)
    {
        return response()->json($book, 200);
    }

    public function update(Request $request, Book $book)
    {
        $book->update($request->all());
        return response()->json($book, 200);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
