<?php
require_once 'vendor/autoload.php';

use Nobaan\Backend\Core\Database;

$db = new Database;

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="app/Frontend/assets/css/styles.css">
	</link>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
	<title>Nobaan Products Page</title>
</head>

<body id="body">

	<section class="section-products">
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-md-8 col-lg-6">
					<div class="header">
						<h3>Featured Product</h3>
						<h2>Popular Products</h2>
					</div>
				</div>
			</div>
			<button class="btn btn-secondary mb-3" id="price_asc">Price Ascending</button>
			<button class="btn btn-secondary mb-3" id="price_des">Price Descending</button>
			<div class="row" id="products_list"></div>
		</div>
	</section>
</body>
<footer>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
		crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"
		integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script>
		window.addEventListener('load', () => {
			load_products();
		})

		document.getElementById('price_asc').addEventListener('click', () => {
			load_products(['ascending'])
		})

		document.getElementById('price_des').addEventListener('click', () => {
			load_products(['descending'])
		})









		function load_products(filters = []) {
			$.ajax({
				type: 'POST',
				url: 'app/Backend/api/ajaxRequests.php',
				data:
				{
					action: 'get_all_products',
					filters: filters
				},
				success: function (data) {
					data = JSON.parse(data)
					// console.log(data);
					if (data === 'false') {
						document.getElementById('products_list').innerHTML = '<h6>No products were found.</h6>'
						return
					}
					if (filters.length && filters[0] === 'ascending') {
						let reserve = {},
							sortable = [],
							counter = 0;
						data.forEach(product => {
							if (product.product_discount) {
								reserve[product.id] = product.product_price * (100 - product.product_discount) / 100;
							} else {
								reserve[product.id] = product.product_price;
							}
						});
						for (var key in reserve) {
							sortable.push([key, reserve[key]]);
						}
						sortable.sort(function (a, b) {
							return a[1] - b[1];
						});
						sortable.forEach(element => {
							data.forEach(product => {
								if (element[0] == product.id){
									const index = data.findIndex(object => {
										return object.id == element[0];
									});
								var spliceditem = data.splice(index, 1)[0]
								data.splice(counter, 0, spliceditem)
							}
							});
							counter++
						});
					} else if (filters.length && filters[0] === 'descending') {
						let reserve = [];
					}
					let html = '';
					data.forEach(product => {
						html += '<div class="col-md-6 col-lg-4 col-xl-3">';
						html += '<div id="product-' + product.product_uid + '" class="single-product">'
						html += '<div class="part-1">'
						html += '<img src="app/Frontend/assets/images/sample.png" alt="sample_picture" />'
						html += '</div>'
						html += '<div class="part-2">'
						html += '<h3 class="product-title">' + product.product_name + '</h3>'
						if (product.product_discount) {
							html += '<h4 class="product-old-price">' + product.product_price + '</h4>'
							html += '<h4 class="product-price">' + product.product_price * (100 - product.product_discount) / 100 + ' Rials</h4>'
						} else {
							html += '<h4 class="product-price">' + product.product_price + ' Rials</h4>'
						}
						html += '</div>'
						html += '</div>'
						html += '</div>'
					});
					document.getElementById('products_list').innerHTML = html
				},
				error: function (xhr, status, error) {
					console.log(xhr);
				}
			})
		}
	</script>
</footer>

</html>