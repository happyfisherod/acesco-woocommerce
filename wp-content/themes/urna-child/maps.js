var $ = jQuery.noConflict();


function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(d{3})(?=d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};




function sortOrder(elm) {
  $('.regular-price').each(function(){

    var newPrice = formatMoney($(this).data('price'))

    $(this).text(newPrice)
    
  });
  $('.price-text').each(function(){

    var newPrice = formatMoney($(this).data('price'))

    $(this).text(newPrice)
    
  });


  elm.sort(function(a, b) {
    var $a = $(a), $b = $(b);

    if ($a.data('shipping') > $b.data('shipping')) {
      return 1;
    } else if ($a.data('shipping') < $b.data('shipping')) {
      return -1;
    }
    if ($a.data('distance') > $b.data('distance')) {
      return 1;
    } else if ($a.data('distance') < $b.data('distance')) {
      return -1;
    }

    return 0;
  });

  $(".comparador .col-md-12").html('');
  $(".comparador .col-md-12").append(elm);
  $('.comparador').show();
  $('.form-comparador').show();
  click();
}





var map, infoWindow;
gmarkers = [];
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 6
  });
  infoWindow = new google.maps.InfoWindow;

  var input = document.getElementById('pac-input');

  var autocomplete = new google.maps.places.Autocomplete(input);

  // Bind the map's bounds (viewport) property to the autocomplete object,
  // so that the autocomplete requests use the current map bounds for the
  // bounds option in the request.
  autocomplete.bindTo('bounds', map);

  // Set the data fields to return when the user selects a place.
  autocomplete.setFields(
      ['address_components', 'geometry', 'icon', 'name']);

  var infowindowContent = document.getElementById('infowindow-content');
  infoWindow.setContent(infowindowContent);

  
  var marker;
  var bounds = new google.maps.LatLngBounds();
  
  $.each($('.dist-loc') , function(){
  
    var loc = $(this).data('location');

    newStr = loc.split(/, ?/);
    var point = new google.maps.LatLng(parseFloat(newStr[0]) , parseFloat(newStr[1]));

    var icon = {
      url: "https://construyamos.com/colombia/wp-content/themes/urna-child/images/marker-dist.svg", // url
      scaledSize: new google.maps.Size(30, 45), // scaled size
      origin: new google.maps.Point(0,0), // origin
      anchor: new google.maps.Point(0, 0) // anchor
  };
    marker = new google.maps.Marker({
      position: point,
      map: map,
      icon: icon
    });
    gmarkers.push(marker);

  });
  Pos();

  autocomplete.addListener('place_changed', function() {
  infoWindow.close();
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      window.alert("No details available for input: '" + place.name + "'");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      marker.setVisible(true);
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }

      var _pCord = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());

      var markerPosicon = {
        url: "https://construyamos.com/colombia/wp-content/themes/urna-child/images/marker-pos.svg", // url
        scaledSize: new google.maps.Size(30, 45), // scaled size
        origin: new google.maps.Point(0,0), // origin
        anchor: new google.maps.Point(0, 0) // anchor
    };
      var markerPos = new google.maps.Marker({
        position: _pCord,
        icon: markerPosicon
      });

      markerPos.setMap(map);

      for( i=0;i<gmarkers.length; i++ ) {
          var distance = google.maps.geometry.spherical.computeDistanceBetween(gmarkers[i].getPosition(), _pCord);

          kilometers = distance / 1000;
          kilometers = kilometers.toFixed(1);

          $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"]').attr('data-distance', kilometers);


          if(kilometers > 100){
            $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"]').hide();
            //$('.woocommerce.sorry').show();
          }else{
            //$('.woocommerce.sorry').hide();

            $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"] .km').text('Distancia: ' + kilometers+'km');
            $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"]').show();

            
            var elm = $('*[data-location]');
            

            sortOrder(elm);

          }
      }
      if($('*[data-location]:visible').length == 0){
        $('.woocommerce.sorry').show();

      }else{
            $('.woocommerce.sorry').hide();

      }


    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }
  });

  google.maps.event.addDomListener(input, 'keydown', function(event) { 
    if (event.keyCode === 13) { 
        event.preventDefault(); 
    }
  }); 

}


function Pos(){
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
  
      var _pCord = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
      var markerPosicon = {
        url: "https://construyamos.com/colombia/wp-content/themes/urna-child/images/marker-pos.svg", // url
        scaledSize: new google.maps.Size(30, 45), // scaled size
        origin: new google.maps.Point(0,0), // origin
        anchor: new google.maps.Point(0, 0) // anchor
    };
      var markerPos = new google.maps.Marker({
        position: _pCord,
        icon: markerPosicon
      });
  
      markerPos.setMap(map);
      map.setCenter(pos);
      map.setZoom(17);
  
  
      for( i=0;i<gmarkers.length; i++ ) {
          var distance = google.maps.geometry.spherical.computeDistanceBetween;
  
          var dis = (distance(gmarkers[i].getPosition(), _pCord)/1000).toFixed(1);
    
          $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"]').attr('data-distance', dis);
  
          if(dis > 100){
            $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"]').hide();
          }else{
            $('*[data-location="'+ gmarkers[i].getPosition().lat() +','+gmarkers[i].getPosition().lng()+'"] .km').text('Distancia: '+dis+'km');
  
            var elm = $('*[data-location]');
  
            


              sortOrder(elm);
              
  
          }
      }
      if($('*[data-location]:visible').length == 0){
            $('.woocommerce.sorry').show();

      }else{
            $('.woocommerce.sorry').hide();

      }
  
  
    }, function(error) {
      if (error.code == error.PERMISSION_DENIED)
      $('.geobox .box-controls button').show();
      $('.form-comparador').hide();
    });
  } else {
    // Browser doesn't support Geolocation

    handleLocationError(false, infoWindow, map.getCenter());
  }
}





var elementArray;
function click(){


  var added = $('.cart_item.added');
  var addedParent = added.parent();
  var carts = addedParent.find('.cart_item');
  var addedLen = added.length + 1;

  if(carts.length === addedLen ){
    addedParent.find('.send-to-cart').remove()
    addedParent.find('.send').html('<a href="https://construyamos.com/" class="send-to-cart cartPage">Finalizar compra</a>');
  }



  $('.send .finalizar-compra-btn').on('click', function(e){
    e.preventDefault();
    $('.modal.not').modal();
  })


  $('.send button.send-to-cart').on('click', function(e){
    e.preventDefault();
  

    var checks = $(this).closest('.dist-loc').find('input[type="checkbox"]:checked');
    var checks2 = $(this).closest('.dist-loc').find('input[type="checkbox"]:checked').length;

    if(checks2 != 0){
      
      $('.form-comparador .ajax-loader-wapper').fadeIn(300);
      $('.form-comparador .comparador').animate({
        opacity: 0.5,
      }, 300, function() {
        $(this).css({'pointer-events' : 'none'})
      });
      callingFunction(checks);
    }else{
      alert('Debe marcar algun producto');
    }
  
  

  

  
  });



$(".plus, .minus").click(function () {

  var qty = $(this).closest('.cart_item').find('.qty'),
      currentVal = parseFloat(qty.val()),
      max = $(qty).attr("max"),
      min = $(qty).attr("min"),
      step = $(qty).attr("step"),

      currentVal = !currentVal || currentVal === '' || currentVal === 'NaN' ? 0 : currentVal;
      max = max === '' || max === 'NaN' ? '' : max;
      min = min === '' || min === 'NaN' ? 0 : min;
      step = step === 'any' || step === '' || step === undefined || parseFloat(step) === NaN ? 1 : step;

      if ($(this).is('.plus')) {
        if (max && (max == currentVal || currentVal > max)) {
          qty.val(max);
          qty.trigger("change");


        } else {
          qty.val(currentVal + parseFloat(step));
          qty.trigger("change");
        }
      } else {
        if (min && (min == currentVal || currentVal < min)) {
          qty.val(min);
          qty.trigger("change");
        } else if (currentVal > 0) {
          qty.val(currentVal - parseFloat(step));
          qty.trigger("change");
        }
      }
});

$.each($('.comparador .input-text.qty.text'), function(){

  var parentItem = $(this).parent().parent().parent().parent();
  var Qty =  $(this).val();
  var Regular = $(parentItem).find('.regular-price').data('price');
  var Input = $(parentItem).find('input[type="checkbox"]');
  var totalQ = Regular * Qty;
  
  $(Input).attr('data-price', Regular);
  $(Input).attr('data-qty', Qty);



  $(this).change(function(){
    var parentItem = $(this).parent().parent().parent().parent();
    var Qty =  $(this).val();
    var Regular = $(parentItem).find('.regular-price').data('price');
    var Input = $(parentItem).find('input[type="checkbox"]');
    var totalQ = Regular * Qty;

    
    $(Input).attr('data-price', Regular);
    $(Input).attr('data-qty', Qty);
    $(parentItem).find('.price-text').text(formatMoney(totalQ));
    $(parentItem).find('.price-text').attr('data-price', totalQ);

      var loc = $(this).closest('.dist-loc');
  
      var ptext = loc.find('.price-text');
      var sum = 0;
      $.each(ptext,function(){
        sum += Number($(this).attr('data-price'));
      });
      
      loc.find('.cart-totals-dist .num').html(formatMoney(sum));
      loc.find('.cart-totals-dist .num').attr('data-price', sum);

    if(Input.is(':checked')){  
       

    }


  });
});

$('.refresh-totals').on('click', function(e){
  e.preventDefault();

  var inputChecked = $(this).closest('.dist-loc').find('input[type="checkbox"]:checked');
  var mod = $(this).closest('.dist-loc').find('.selected-totals-dist span .num');
  var sum = 0;
  $.each(inputChecked, function(){
    var after = $(this).closest('.dist-loc').find('.selected-totals-dist span .num').html();
    var qt = $(this).closest('.cart_item').find('.qty').val();
    var price = $(this).data('price');
    var qty = qt;
    var total =  price * qty;

    var Totals = parseInt(total);
    sum += Number(Totals);
  });
  
  mod.html(formatMoney(sum));
  mod.data('price', sum);

})
$('.dist-loc input[type="checkbox"]').change(function(e){

  var after = $(this).closest('.dist-loc').find('.selected-totals-dist span .num').data('price');
  var mod = $(this).closest('.dist-loc').find('.selected-totals-dist span .num').data('price');
  var tot = $(this).closest('.cart_item').find('.qty').val();
  var qt = $(this).closest('.cart_item').find('.qty');
  var refresh = $(this).closest('.dist-loc').find('.refresh-totals');
  refresh.prop('disabled', false);





})


$('.dist-loc').each(function(){
  if($(this).is(':visible')){
    var prices = $(this).find('.price-text');
    var sum = 0;
    prices.each(function(){
      sum += Number($(this).data('price'));
    });
  }
  $(this).find('.cart-totals-dist .num').html(formatMoney(sum));
  $(this).find('.cart-totals-dist .num').attr('data-price', sum);
});
}

function callingFunction(checks){
    elementArray = new Array();
    checks.each(function () {
        elementArray.push($(this));
    });
    doAjax(0);

}


function doAjax(arrCount){
  var qty = elementArray[arrCount].closest('.cart_item').find('.qty').val();
  var pid = elementArray[arrCount].data('product-id');
  var vid = elementArray[arrCount].data('product-var');
  

  var data = {
    'action': 'addtocart',
    'product_id':pid,
    'cant':qty,
    'var_id':vid,
  };
  $.post(ajaxurl, data, function(response) {

    arrCount++;
    if (arrCount < elementArray.length){
        doAjax(arrCount);
    }else{
        var url = response; 
        window.location.href = url;   
    }   
  });
}



var perfEntries = performance.getEntriesByType("navigation");

if (perfEntries[0].type === "back_forward") {
    location.reload(true);
}