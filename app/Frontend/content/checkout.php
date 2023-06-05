<div class="d-none" id="product_order_id"></div>
<div class="container mt-5 p-5">
    <h2>Product Order Purchase</h2>
    <form action="thank_you.php" method="POST">
        <div class="mb-3 d-none" id="error_section">
            <p id="error_log" style="color: red;"></p>
        </div>
        <div class="mb-3">
            <p>Price =
                <?php if (isset($product_price))
                    echo $product_price;
                else
                    echo '?' ?>
                </p>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" placeholder="09xxx">
            </div>
            <div class="mb-3">
                <label for="Product_uid" class="form-label">Product code</label>
                <input type="text" class="form-control" id="Product_uid" value="<?= $uid ?>" disabled>
        </div>
        <button class="d-none" id="submit_form" name="submit">Submit</button>
    </form>
    <div class="mb-3">
        <button class="btn btn-primary" id="check_number">Submit</button>
    </div>
</div>