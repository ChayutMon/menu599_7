@extends('layouts.luxury-nav')

@section('title', 'หน้ารายละเอียด')

@section('content')
    <?php
    
    use App\Models\Config;
    
    $config = Config::first();
    ?>
    <style>
        .title-buy {
            font-size: 30px;
            font-weight: bold;
            color: <?=$config->color_font !='' ? $config->color_font : '#ffffff' ?>;
        }

        .title-list-buy {
            font-size: 25px;
            font-weight: bold;
        }

        .btn-plus {
            background: none;
            border: none;
            color: rgb(0, 156, 0);
            cursor: pointer;
            padding: 0;
            font-size: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-plus:hover {
            color: rgb(185, 185, 185);
        }

        .btn-delete {
            background: none;
            border: none;
            color: rgb(192, 0, 0);
            cursor: pointer;
            padding: 0;
            font-size: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            color: rgb(185, 185, 185);
        }

        .btn-aprove {
            background: linear-gradient(360deg, var(--primary-color), var(--sub-color));
            border-radius: 20px;
            border: 0px solid #0d9700;
            padding: 5px 0px;
            font-weight: bold;
            text-decoration: none;
            color: rgb(255, 255, 255);
            transition: background 0.3s ease;
        }

        .btn-aprove:hover {
            background: linear-gradient(360deg, var(--sub-color), var(--primary-color));
            cursor: pointer;
        }

        .btn-edit {
            background: transparent;
            color: rgb(206, 0, 0);
            border: none;
            font-size: 12px;
            text-decoration: underline;
            padding: 0;
            margin-top: -8px;
            cursor: pointer;
        }
    </style>

    <div class="container">
        <div class="d-flex flex-column justify-content-center gap-2">
            <div class="title-buy">
                คำสั่งซื้อ
            </div>
            <a href="javascript:void(0);" class="btn-aprove mt-3" id="confirm-order-btn"
                style="display: none;">ยืนยันคำสั่งซื้อ</a>
            <div class="bg-white px-2 pt-3 shadow-lg d-flex flex-column aling-items-center justify-content-center"
                style="border-radius: 10px;">
                <div class="title-list-buy">
                    รายการอาหารที่สั่ง
                </div>
                <div id="order-summary" class="mt-2"></div>
                <div class="d-flex justify-content-center mt-5 mb-4" id="order-actions" style="display:none;">
                    <button type="button" class="btn btn-danger btn-sm fw-bold px-4" id="clear-cart-btn">เคลียร์ตะกร้าทั้งหมด</button>
                </div>
                <div class="fw-bold fs-5 mt-5 " style="border-top:2px solid #7e7e7e; margin-bottom:-10px;">
                    ยอดชำระ
                </div>
                <div class="fw-bold text-center" style="font-size:45px; ">
                    <span id="total-price" style="color: #0d9700"></span><span class="text-dark ms-2">บาท</span>
                </div>
            </div>

            <a href="javascript:void(0);" class="btn-aprove mt-3" id="confirm-order-btn"
                style="display: none;">ยืนยันคำสั่งซื้อ</a>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('order-summary');
            const totalPriceEl = document.getElementById('total-price');
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            console.log(cart)



            function renderOrderList() {
                container.innerHTML = '';
                let total = 0;
                const orderActions = document.getElementById('order-actions');
                if (cart.length === 0) {
                    const noItemsMessage = document.createElement('div');
                    noItemsMessage.textContent = "ท่านยังไม่ได้เลือกสินค้า";
                    container.appendChild(noItemsMessage);
                    if(orderActions) orderActions.style.display = 'none';
                } else {
                    if(orderActions) orderActions.style.display = 'flex';
                    const mergedItems = {};
                    cart.forEach(item => {
                        if (!mergedItems[item.name]) {
                            mergedItems[item.name] = [];
                        }
                        mergedItems[item.name].push(item);
                    });

                    for (const name in mergedItems) {
                        const groupedItems = mergedItems[name];
                        let totalPrice = 0;

                        groupedItems.forEach(item => {
                            totalPrice += item.total_price;

                            const optionsText = (item.options && item.options.length) ?
                                item.options.map(opt => opt.label).join(', ') :
                                '-';

                            const row = document.createElement('div');
                            row.className =
                                'row justify-content-between align-items-start fs-6 mb-2 text-start px-1';

                            const leftCol = document.createElement('div');
                            leftCol.className = 'col-9 d-flex flex-column justify-content-start lh-sm';

                            const title = document.createElement('div');
                            title.className = 'card-title m-0';
                            title.textContent = item.name + " x" + item.amount;

                            const optionTextEl = document.createElement('div');
                            optionTextEl.className = 'text-muted';
                            optionTextEl.style.fontSize = '12px';
                            optionTextEl.textContent = optionsText;

                            leftCol.appendChild(title);
                            leftCol.appendChild(optionTextEl);

                            const rightCol = document.createElement('div');
                            rightCol.className = 'col-2 d-flex flex-column align-items-end';

                            const priceText = document.createElement('div');
                            priceText.textContent = item.total_price.toLocaleString();

                            const editBtn = document.createElement('a');
                            editBtn.className = 'btn-edit';
                            editBtn.textContent = 'แก้ไข';
                            editBtn.href = `/detail/${item.category_id}#select-${item.id}&uuid=${item.uuid}`;

                            rightCol.appendChild(priceText);
                            rightCol.appendChild(editBtn);

                            row.appendChild(leftCol);
                            row.appendChild(rightCol);
                            container.appendChild(row);
                        });
                        total += totalPrice;
                    }
                }
                totalPriceEl.textContent = total.toLocaleString();
            }

            renderOrderList();


            const confirmButton = document.getElementById('confirm-order-btn');

            function toggleConfirmButton(cart) {
                if (cart.length > 0) {
                    confirmButton.style.display = 'inline-block';
                } else {
                    confirmButton.style.display = 'none';
                }
            }

            toggleConfirmButton(cart);

            confirmButton.addEventListener('click', function(event) {
                event.preventDefault();
                if (cart.length > 0) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('SendOrder') }}",
                        data: {
                            cart: cart,
                            remark: $('#remark').val()
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == true) {
                                Swal.fire(response.message, "", "success");
                                localStorage.removeItem('cart');
                                cart = [];
                                toggleConfirmButton(cart);
                                setTimeout(() => {
                                    location.reload();
                                }, 3000);
                            } else {
                                Swal.fire(response.message, "", "error");
                                toggleConfirmButton(cart);
                            }
                        }
                    });
                }
            });

            // Only clear cart
            const clearCartBtn = document.getElementById('clear-cart-btn');
            if(clearCartBtn) {
                clearCartBtn.addEventListener('click', function() {
                    if (cart.length === 0) return;
                    Swal.fire({
                        title: 'ลบตะกร้าทั้งหมด?',
                        text: 'คุณต้องการลบสินค้าทั้งหมดออกจากตะกร้าหรือไม่?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ใช่, ลบทั้งหมด',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cart = [];
                            localStorage.removeItem('cart');
                            renderOrderList();
                            toggleConfirmButton(cart);
                        }
                    });
                });
            }

        });
    </script>




@endsection
