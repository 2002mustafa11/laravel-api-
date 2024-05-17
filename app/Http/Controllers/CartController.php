<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCartInstance($userID)
    {
        return Cart::instance('shopping_' . $userID);
    }

    public function addProductToCart(Request $request, $productId)
    {
        $product = Product::find($productId);
        $userID = $request->user()->id; // معرف المستخدم الحالي
        
        $this->getCartInstance($userID)->add([
            'id' => $productId,
            'name' => $product->name,
            'qty' => $request->input('quantity', 1),
            'price' => $product->price,
            'options' => []
            ])->associate($product);
            // return response()->json(['message' => 'Product added to cart'], 201);

        $items = $this->getCartInstance($userID)->content();
        return response()->json(['message' => 'Product added to cart', 'items' => $items], 201);
    }

    public function updateCart(Request $request, $rowId)
    {
        $userID = $request->user()->id;

        $updateData = [];
        if ($request->has('quantity')) {
            $updateData['qty'] = $request->input('quantity');
        }
        if ($request->has('price')) {
            $updateData['price'] = $request->input('price');
        }

        $this->getCartInstance($userID)->update($rowId, $updateData);

        return response()->json(['message' => 'Cart updated']);
    }

    public function removeProductFromCart(Request $request, $rowId)
    {
        $userID = $request->user()->id;

        $this->getCartInstance($userID)->remove($rowId);

        return response()->json(['message' => 'Product removed from cart']);
    }

    public function viewCart(Request $request)
    {
        $userID = $request->user()->id;
        $items = $this->getCartInstance($userID)->content();

        return response()->json($items);
    }

    public function destroyCart(Request $request)
    {
        $userID = $request->user()->id;

        $this->getCartInstance($userID)->destroy();

        return response()->json(['message' => 'Cart destroyed']);
    }

    public function getTotal(Request $request)
    {
        $userID = $request->user()->id;
        $total = $this->getCartInstance($userID)->total();

        return response()->json(['total' => $total]);
    }
}
