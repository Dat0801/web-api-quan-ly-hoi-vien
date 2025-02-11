<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Market;

class MarketController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $markets = Market::when($search, function ($query, $search) {
            return $query->where('market_name', 'LIKE', '%' . $search . '%');
        })->paginate(3);

        return view('category.market.index', compact('markets'));
    }

    public function create()
    {
        return view('category.market.create');
    }

    public function store(Request $request)
    {
        Market::create($request->all());
        return redirect()->route('market.index')->with('success', 'Thêm thị trường thành công.');
    }

    public function show($id)
    {
        $market = Market::findOrFail($id);
        return view('category.market.show', compact('market'));
    }

    public function edit($id)
    {
        $market = Market::findOrFail($id);
        return view('category.market.edit', compact('market'));
    }

    public function update(Request $request, $id)
    {
        $market = Market::findOrFail($id);
        $market->update([
            'market_code' => $request->market_code,
            'market_name' => $request->market_name,
            'description' => $request->description,
        ]);

        return redirect()->route('market.index')->with('success', 'Cập nhật thị trường thành công!');
    }

    public function destroy(Market $market)
    {
        $market->delete();
        return redirect()->route('market.index')->with('success', 'Xóa thị trường thành công.');
    }
}
