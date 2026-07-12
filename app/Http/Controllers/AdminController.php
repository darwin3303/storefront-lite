<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

use App\Models\Order;

use App\Models\Product;

use Faker\Provider\ar_EG\Internet;

use Flasher\Prime\FlasherInterface;

use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function view_category()
    {
        $data = category::all();

        return view('admin.category', compact('data'));
    }

    public function add_category(Request $request)
    {
        //return $request;
        //this is one of way to validate request
        if ($request->category) {
            $Category = new Category;

            $Category->category_name = $request->category;

            $Category->save();

            flash()->success('category Added Successfully.');

            return redirect()->back();
        } else {
            flash()->error('category name is required.');
            return redirect()->back();
        }
    }

    public function delete_category($id)
    {
        //this if check when is $id is null
        if ($id !== null) {  //same -> !is_null($id)

            //   then id is came checking category table related id data is retivew
            $data = category::find($id);

            //if data is found delete
            if ($data) {
                $data->delete();
                flash()->success('category deleted Successfully.');
                return redirect()->back();
            } else {
                flash()->error('data not found!.');
                return redirect()->back();
            }
            //then if null show error messgae no id found
        } else {
            flash()->error('id is required.');
            return redirect()->back();
        }
    }

    public function edit_category($id)
    {
        $data = category::find($id);
        return view('admin.edit_category', compact('data'));
    }

    public function update_category(Request $request, $id)
    {

        $data = category::find($id);

        $data->category_name = $request->category;

        $data->save();

        flash()->success('category update Successfully.');

        //return redirect()->route('category_page');
        return redirect('/admin/category');
        //return redirect()->back();

    }

    public function add_product()
    {

        $category = category::all();

        return view('admin.add_product', compact('category'));
    }

    public function upload_product(Request $request)

    {

        // return $request; 
        $data = new Product;

        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->qty;
        $data->category = $request->category;
        $image = $request->image;

        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('products', $imagename);
            $data->image = $imagename;
        }

        $data->save();
        flash()->success('Added Successfully.');
        return redirect()->back();
    }

    public function view_product()
    {
        $product = Product::paginate(3);
        return view('admin.view_product', compact('product'));
    }


    public function delete_product($id)
    {
        $data = Product::find($id);

        //build the image path
        $image_path = public_path('products/' . $data->image);

        //check if file exists for delete
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        //delete data in database
        $data->delete();


        flash()->success('Delete Successfully.');
        return redirect()->back();
    }

    public function update_product($slug)
    {
        $data = product::where('slug',$slug)->get()->first();
        $category = category::all();
        return view('admin.update_page', compact('data', 'category'));
    }


    public function edit_product(Request $request, $id)
    {
        $data = Product::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->price = $request->price;
        $data->quantity = $request->quantity;
        $data->category = $request->category;
        $image = $request->image;



        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('products', $imagename);
            $data->image = $imagename;
        }

        $data->save();
        flash()->success('Product Updated Successfully.');
        return redirect('/view_product');
    }

    public function product_search(Request $request)
    {
        //secound way with error handling

        try {
            $search = $request->search;

            $product = Product::where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('category', 'LIKE', '%' . $search . '%')
                ->paginate(3);

            if ($product->isEmpty()) {
                flash()->error('No products found for: ' . $search);
                return redirect()->back();
            }

            return view('admin.view_product', compact('product'));
        } catch (\Exception $e) {
            Log::error('Search error', ['exception' => $e->getMessage()]);
            flash()->error('Something went wrong while searching. Please try again');
            return redirect()->back();
        }




        //firsy way 

        //     $search = $request->search;

        //     $product = product::where('title','LIKE','%'.$search.'%')
        //         ->orWhere('category','LIKE','%'.$search.'%')
        //         ->paginate(3);

        //     return view('admin.view_product', compact('product'));

    }

    public function order()
    {
        $data = Order::all();

        return view('admin.order', compact('data'));
    }

    public function on_the_way($id)
    {
        $data = Order::find($id);

        $data->status = 'On the way';

        $data->save();

        return redirect('/admin/orders');
    }

    public function delivered($id)
    {
        $data = Order::find($id);

        $data->status = 'Delivered';

        $data->save();

        return redirect('/admin/orders');
    }

    public function print_pdf($id)
    {
        $data = Order::find($id);

        $pdf = Pdf::loadView('admin.invoice', compact('data'));

        return $pdf->download('invoice.pdf');
    }
}
