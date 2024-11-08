<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Vanding Machine</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .machine-container {
      max-width: 900px;
      margin: 50px auto;
      padding: 20px;
      border: 2px solid #ccc;
      border-radius: 10px;
      background-color: #f7f7f7;
    }

    .display-screen {
      height: 50px;
      text-align: center;
      font-size: 1.2em;
      line-height: 50px;
      background-color: #333;
      color: #fff;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    .product-item {
      background-color: #000000;
      color: #fff;
      border-radius: 10px;
    }

    .product-detail {
      width: 350px;
      height: 150px;
    }
  </style>
</head>
<body>

<div class="machine-container text-center">
  <h2>Vending Machine</h2>
  <div class="display-screen" id="display">Select an Item</div>

  <div class="row col-12 ml-1">
    @foreach ($products as $item)
    <div class="card col-6" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">{{$item->name}}</h5>
        <p class="card-text">$ {{$item->price}}</p>
        <button href="#" class="btn btn-primary item-button" data-item="{{$item->id}}" data-quantity="{{$item->quantity}}" data-name="{{$item->name}}" data-price="{{$item->price}}" >Get</button>
      </div>
    </div>
    @endforeach
  </div>
  
  <div class="d-flex justify-content-around">
    <button class="btn btn-success mt-4" id="purchaseButton">Purchase</button>
    <button class="btn btn-info mt-4" id="resetButton">Reset</button>
  </div>

</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
  $(document).ready(function() {
    let selectedId = null;
    let selectedItem = null;
    let itemCount = 0;
    let amount = 0;
    
    $('.item-button').click(function() {

      if (selectedId != $(this).data('item')) {
        selectedId = $(this).data('item');
        selectedItem = $(this).data('name');
        itemCount = 0;
        amount = 0;
      }

      selectedId = $(this).data('item');
      selectedItem = $(this).data('name');
      itemCount += 1;
      amount += $(this).data('price');
      let totalItem = $(this).data('quantity');

      if(itemCount > totalItem) {
        swal({
            title: "Warning",
            text: "Selected is exceed than available quantity",
            icon: "warning",
            dangerMode: true,
          })
          .then(() => {
            location.reload();
          });
      }

      $('#display').text('Selected Item: ' + selectedItem + ' ('+ itemCount +' qty ) Total Amount $ ' + amount);
    });

    
    $('#purchaseButton').click(function() {
      if (selectedItem) {
        $('#display').text('Dispensing ' + selectedItem + '...');
        
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        setTimeout(function() {
          $('#display').text('Select an Item');
          selectedItem = null;
        }, 2000);

        $.ajax({
            url: "/make-transaction", 
            type: "POST",
            data: {
                _token: csrfToken, 
                product_id: selectedId,
                quantity: itemCount,
                total_amount: amount
            },
            success: function(response) {
                if(response.success) {
                  swal("Thank You!", response.message, "success");
                }
            },
            error: function(error) {
              swal("Sorry!", response.message, "danger"); // Log any error
            }
        });

      } else {
        swal("Warning!", "Please Choose an item first!", "warning");
      }
    });

    $('#resetButton').click(function() {
      location.reload();
    })
  });
</script>

</body>
</html>
