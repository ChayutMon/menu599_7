<?php

use App\Models\Config;

$config = Config::first();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'So Fin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-PapQnVEUR/..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- ✅ Bootstrap 5.3.0 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?= $config->color1 ?? '#1479eb' ?>;
            --sub-color: <?= $config->color2 ?? '#84d2f8' ?>;
            --text-color: <?= $textColorHover ?? '#f0f0f0' ?>;
            --bg-card-food: <?= $bgCardFod ?? '#ffffff' ?>;
        }


        @font-face {
            font-family: 'PROMPT';
            src: url('{{ asset("fonts/PROMPT-LIGHT.TTF") }}') format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'PROMPT';
            src: url('{{ asset("fonts/PROMPT-SEMIBOLD.TTF") }}') format('truetype');
            font-weight: bold;
        }

        body {
            margin: 0;
            padding-bottom: 90px; /* เพิ่มจาก 30px เป็น 90px เพื่อให้พื้นที่สำหรับ navbar ที่ใหญ่ขึ้น */
            font-family: 'PROMPT', sans-serif;
            background-image: url('{{ $config->image_bg ? url("storage/".$config->image_bg) : asset("bg-df/bg-dfs.jpg") }}');
            background-size: cover;
            background-position: center;
            /* background-repeat: no-repeat; */
            /* min-height: 30vh; */
            color: #333333;
            transition: background-image 0.3s ease, color 0.3s ease;
        }

        .bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 80px; /* เพิ่มความสูงจาก default เป็น 80px */
            background-color: rgba(255, 255, 255, 0.95);
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.15); /* เพิ่มเงาให้เข้มขึ้น */
            z-index: 999;
            transition: transform 0.3s ease;
            padding: 10px 0; /* เพิ่ม padding บนล่าง */
        }

        .bottom-navbar.hide {
            transform: translateY(100%);
        }

        .bottom-navbar a {
            flex: 1;
            text-align: center;
            text-decoration: none;
            color: var(--primary-color);
            font-size: 14px; /* เพิ่มจาก 0.75rem (12px) เป็น 14px */
            padding: 8px 12px; /* เพิ่ม padding */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-weight: 500; /* เพิ่มความหนาตัวอักษร */
        }

        .bottom-navbar a:hover {
            color: var(--text-color);
            background: linear-gradient(360deg, var(--primary-color), var(--sub-color));
            transform: scale(1.05);
            border-radius: 10px; /* เพิ่มขอบโค้งเมื่อ hover */
        }

        .bottom-navbar a.active {
            color: var(--text-color);
        }

        .bottom-navbar a .icon {
            font-size: 28px; /* เพิ่มจาก 1.5rem (24px) เป็น 28px */
            margin-bottom: 6px; /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
        }

        .bottom-navbar a .icon i {
            font-size: 28px; /* เพิ่มขนาดไอคอน Font Awesome */
        }

        main {
            padding: 1rem;
            text-align: center;
        }

        /* หากต้องการให้ใหญ่มากขึ้นอีก ใช้ CSS นี้แทน */
        /*
        .bottom-navbar {
            height: 100px;
            padding: 15px 0;
        }

        .bottom-navbar a {
            font-size: 16px;
            padding: 10px 15px;
        }

        .bottom-navbar a .icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        body {
            padding-bottom: 110px;
        }
        */
    </style>
</head>

<body>

    <main>
        @yield('content')
    </main>

    <nav class="bottom-navbar" id="bottomNavbar">
        <a href="/" class="">
            <div class="icon"><i class="fas fa-home"></i></div> <!-- ✅ ไอคอนจาก Font Awesome -->
            <div>หน้าแรก</div>
        </a>
        <a href="javascript:void(0);" id="sendEmp">
            <div class="icon"><i class="fas fa-user"></i></div> <!-- ✅ ไอคอนจาก Font Awesome -->
            <div>เรียกพนักงาน</div>
        </a>
        <a href="{{route('order')}}">
            <div class="icon"><i class="fas fa-receipt"></i></div> <!-- ✅ ใช้ icon แสดงคำสั่งซื้อ -->
            <div>คำสั่งซื้อ</div>
        </a>
    </nav>
    <!-- ✅ Bootstrap 5.3.0 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <!-- ✅ Javascript -->
    <script>
        let lastScrollTop = 0;
        const navbar = document.getElementById('bottomNavbar');

        window.addEventListener("scroll", function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > lastScrollTop) {
                navbar.classList.add("hide");
            } else {
                navbar.classList.remove("hide");
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }, false);
    </script>
    <script>
        $('#sendEmp').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{route('sendEmp')}}",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire(response.message, "", "success");
                    } else {
                        Swal.fire(response.message, "", "error");
                    }
                }
            });
        });
    </script>
</body>

</html>