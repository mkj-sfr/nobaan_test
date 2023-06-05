// Scripts
if(document.getElementById('home_id') !== null)
{
    window.addEventListener('load', () => {
        load_products();
    })
    
    document.getElementById('price_asc').addEventListener('click', () => {
        load_products(['ascending'])
    })
    
    document.getElementById('price_des').addEventListener('click', () => {
        load_products(['descending'])
    })
}

if(document.getElementById('product_order_id') !== null)
{
    document.getElementById('check_number').addEventListener('click', () => {
        if (document.getElementById('phone_number').value.match("^09[0-9]{9}$")) {
            $.ajax({
                type: 'POST',
                url: 'app/Backend/api/ajaxRequests.php',
                data:
                {
                    action: 'check_number',
                    phone_number: document.getElementById('phone_number').value,
                    product_uid: document.getElementById('Product_uid').value
                },
                success: function (data) {
                    if (data)
                        document.getElementById('submit_form').click();
                    else {
                        document.getElementById('error_section').classList.remove('d-none');
                        document.getElementById('error_log').innerHTML = "You can only purchase one cut rated product";
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                }
            })
        } else {
            document.getElementById('error_section').classList.remove('d-none');
            document.getElementById('error_log').innerHTML = "Please enter a valid phone number";
        }
    })
}

// Functions

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
                    return b[1] - a[1];
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
                html += '<button class="btn btn-primary" onclick="buy_product(\''+product.product_uid+'\')">Buy</button>'
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

function buy_product(uid)
{
    window.location.href = "http://localhost/nobaan_test_project/product_order.php?product_uid="+uid
}