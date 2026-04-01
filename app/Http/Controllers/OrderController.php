<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    public function step4(Request $request)
    {
        // make sure files exist
        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $file) {

                // generate unique name
                $filename = time() . '_' . $file->getClientOriginalName();

                // store file
                $path = $file->storeAs('uploads', $filename, 'public');

                // save to database
                OrderDetail::create([
                    'order_id' => session('order_id'), // make sure this exists
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('order.step4')
            ->with('success', 'Files uploaded!');
    }
}
