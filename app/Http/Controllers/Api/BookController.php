<?php
namespace App\Http\Controllers\Api;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        //  Search (title / author)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'latest');
        $order  = $request->get('order', 'desc');

        if ($sortBy === 'price') {
            $query->orderBy('price', $order === 'asc' ? 'asc' : 'desc');
        } else {
            // default latest
            $query->latest();
        }

        //  Pagination
        $perPage = $request->get('per_page', 5);
        $books   = $query->paginate($perPage);

        return ApiResponse::success(
            BookResource::collection($books),
            'Books fetched successfully',
            200,
            [
                'current_page' => $books->currentPage(),
                'total'        => $books->total(),
                'per_page'     => $books->perPage(),
            ]
        );
    }

    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());
        return ApiResponse::success(
            new BookResource($book),
            'Book created successfully',
            201
        );

    }

    public function show(Book $book)
    {
        return ApiResponse::success(
            new BookResource($book),
            'Book details fetched'
        );

    }

    public function update(StoreBookRequest $request, Book $book)
    {
        $book->update($request->validated());
        return ApiResponse::success(
            new BookResource($book),
            'Book updated successfully'
        );

    }

    public function destroy(Book $book)
    {
        $book->delete();
        return ApiResponse::success(
            null,
            'Book deleted successfully'
        );

    }
}
