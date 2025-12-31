# Cart Controller Refactoring - DTOs and Service Layer

## Overview
The CartController has been refactored to use DTOs (Data Transfer Objects) and a CartService layer. This follows SOLID principles and improves code maintainability, testability, and reusability.

## Created Files

### 1. **DTOs (Data Transfer Objects)**

#### `app/DTOs/CartItemDTO.php`
Represents a single cart item with the following properties:
- `id`: Cart item ID
- `product_id`: Product ID
- `product_name`: Product name
- `price`: Unit price
- `quantity`: Quantity in cart
- `subtotal`: Price × Quantity
- `product`: Product object (optional)

Methods:
- `fromArray(array)`: Create DTO from array
- `toArray()`: Convert DTO to array

#### `app/DTOs/CheckoutDTO.php`
Represents checkout form data:
- `shipping_address`: Delivery address
- `phone`: Contact phone number
- `name`: Customer name (for guests)
- `email`: Customer email (for guests)

Methods:
- `fromArray(array)`: Create DTO from request data
- `toArray()`: Convert to array

#### `app/DTOs/CartDTO.php`
Represents the complete cart with:
- `items`: Collection of CartItemDTO objects
- `total`: Total cart amount

Methods:
- `fromArray(array)`: Create from array
- `toArray()`: Convert to array
- `getItemCount()`: Get number of items
- `isEmpty()`: Check if cart is empty

### 2. **CartService** (`app/Services/CartService.php`)

The service encapsulates all cart-related business logic:

**Main Methods:**
- `getCart(?User)`: Get cart for authenticated user or guest
- `addToCart(Product, quantity, ?User)`: Add product to cart
- `removeFromCart($cartId, ?User)`: Remove item from cart
- `updateCartItem(quantity, $cartId, ?User)`: Update quantity with price recalculation
- `clearCart(?User)`: Clear entire cart
- `processCheckout(CheckoutDTO, ?User)`: Create order from cart

**Private Helper Methods:**
- `getAuthenticatedCart(User)`: Get user's database cart
- `getSessionCart()`: Get guest's session cart
- `formatSessionCart(array)`: Convert session data to DTOs
- `addToAuthenticatedCart()`: Add to user's cart
- `addToSessionCart()`: Add to session cart
- `calculatePriceByQuantity()`: Calculate tiered pricing

## Refactored CartController

The controller now:
1. Uses dependency injection for CartService
2. Is much cleaner and more readable
3. Delegates business logic to the service
4. Has better separation of concerns
5. Is easier to test

### Controller Methods:
- `index()`: Display cart
- `add()`: Add product to cart
- `remove()`: Remove item from cart
- `update()`: Update item quantity
- `clear()`: Clear entire cart
- `checkout()`: Show checkout page
- `processCheckout()`: Process order creation

## Benefits

1. **Separation of Concerns**: Business logic is in the service, not the controller
2. **Reusability**: CartService can be used in other controllers or APIs
3. **Testability**: Service can be easily mocked and unit tested
4. **Type Safety**: DTOs provide type hints for IDE autocomplete
5. **Maintainability**: Centralized business logic makes updates easier
6. **Code Duplication**: Eliminated duplicate code between authenticated and guest paths
7. **Scalability**: Easy to extend with new features

## Usage Example

```php
// In a controller or elsewhere
public function __construct(private CartService $cartService)
{
}

public function showCart()
{
    $user = Auth::user();
    $cartData = $this->cartService->getCart($user);
    
    return view('cart', [
        'items' => $cartData->items,
        'total' => $cartData->total,
    ]);
}
```

## Data Flow

```
Controller Request
    ↓
CartService (Business Logic)
    ↓
Models (Database/Session)
    ↓
DTOs (Data Transfer)
    ↓
View/Response
```

## Next Steps

1. Update any views that access cart data to work with DTOs if needed
2. Add unit tests for CartService
3. Consider adding validation rules to DTOs
4. Add error handling as needed
5. Consider caching cart data for better performance

