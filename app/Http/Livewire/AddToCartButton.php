<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Design;
use App\Models\DesignPurchase;
use Illuminate\Support\Str;
use Livewire\Component;
class AddToCartButton extends Component
{
    public Design $design;
    public bool $isInCart;
    public bool $isOwned;

    public function mount(Design $design, bool $isInCart)
    {
        $this->design = $design;
        $this->isInCart = $isInCart;
        $this->isOwned = $design->sales->where('user_id', auth()->id())->isNotEmpty();
    }

    public function addToCart()
    {
        if ($this->design->price <= 0) {
            if ($this->isOwned) {
                session()->flash('message', 'You already own this design!');
                return;
            }

            DesignPurchase::create([
                'user_id' => auth()->id(),
                'design_id' => $this->design->id,
                'transaction_id' => 'FREE-' . Str::random(10),
                'amount' => 0.00,
                'purchased_at' => now()
            ]);

            $this->isOwned = true;
            session()->flash('message', 'Free design added to your library!');
            return;
        }

        if ($this->isInCart) {
            session()->flash('message', 'Design already in cart!');
            return;
        }

        $cart = auth()->user()->cart()->firstOrCreate();
        $cart->items()->create([
            'design_id' => $this->design->id,
            'price' => $this->design->price
        ]);

        $this->isInCart = true;
        session()->flash('message', 'Added to cart!');
        $this->dispatch('cart-updated');
    }
}?>
