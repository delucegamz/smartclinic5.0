@extends( 'layouts.medrec' )

@section( 'diagnosis.scripts' )
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
	var options = {
        url: function(phrase) {
            return '{{ route( 'diagnosis.index' ) }}/search';
        },
        getValue: function(element) {
            return element.display_name;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: { val : '' }
        },
        preparePostData: function(data) {
            data.val = $("#icdx").val();
            return data;
        },
        requestDelay: 200,
        list: {
            maxNumberOfElements: 10,
            onSelectItemEvent: function() {
                var selectedItemValue = $("#icdx").getSelectedItemData();
            },
            onClickEvent: function() {
                var selectedItemValue = $("#icdx").getSelectedItemData();
            },
            onHideListEvent: function() {
                
            },
            onChooseEvent: function(){
                var selectedItemValue = $("#icdx").getSelectedItemData();

                $('#icdx-text').val(selectedItemValue.nama_diagnosa);
                $('#icdx-id').val(selectedItemValue.id_diagnosa);
                $('#icdx').val(selectedItemValue.kode_diagnosa);
            }
        }
    };

    $("#icdx").easyAutocomplete(options);

    $('#medrec-form').validate({
    	errorPlacement: function(error, element) { 
            var selector = $(element.context).attr('id');

            error.appendTo($('#' + selector).parents('div[class*="col-xs"]').find('.error-placement'));
        }
    });

    $('a[data-toggle="tab"]').not('#home-tab').on('click', function (e) {
        if(!$('#medrec-form').valid()){
            alert('Harap isikan semua field yang wajib diisi!');
            return false;
        }
    });
});
</script>
@stop

@section( 'action.scripts' )
<script type="text/javascript">
$(document).ready(function(){
	$('#other-action-form input[type="checkbox"]').change(function() {
		if($(this).prop('checked')){
			$(this).val('1');
		}else{
			$(this).val('0');
		}
	});
});
</script>
@stop

@section( 'allergic.scripts' )
<script type="text/javascript">
$(document).ready(function(){
	var options = {
        url: function(phrase) {
            return '{{ route( 'medicine.index' ) }}/search_med_by_code_or_name';
        },
        getValue: function(element) {
            return element.display_name;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: { val : '' }
        },
        preparePostData: function(data) {
            data.val = $("#medicine-code").val();
            return data;
        },
        requestDelay: 200,
        list: {
            maxNumberOfElements: 10,
            onSelectItemEvent: function() {
                var selectedItemValue = $("#medicine-code").getSelectedItemData();
            },
            onClickEvent: function() {
                var selectedItemValue = $("#medicine-code").getSelectedItemData();
            },
            onHideListEvent: function() {
                
            },
            onChooseEvent: function(){
                var selectedItemValue = $("#medicine-code").getSelectedItemData();

                $('#medicine-code').val(selectedItemValue.display_name);
                $('#medicine-id').val(selectedItemValue.id_obat);
            }
        }
    };

    $("#medicine-code").easyAutocomplete(options);

    $('#btn-save-medicine').click(function(){
    	$medicine_id = $('#medicine-id').val();

    	if($medicine_id == ''){
    		alert('Harap pilih obat yang akan ditambahkan ke list daftar alergi obat.');

    		return false;
    	}

    	$.ajax({
            url: '{{ url( 'medicine' ) }}/' + $medicine_id,
            type: 'GET',
            data: {},
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
                if(json.success == 'true'){
                    if($('#list-medicines tbody tr#item-' + json.id_obat).not('.hide').length > 0){
                        alert("Obat sudah ada di dalam list pembelian obat.");
                        return false;
                    }

                    $count_item = $('#list-medicines tbody tr.item').length;

                    $count_item++;
                    
                    $('#list-medicines tbody tr.no-data').remove();
                        
                    $html = '<tr class="item" id="item-' + json.id_obat + '">\
                                <td class="column-no">' + $count_item + '</td>\
                                <td class="column-group">' + json.jenis_obat + '</td>\
                                <td class="column-code">' + json.kode_obat + '</td>\
                                <td class="column-name">' + json.nama_obat + '</td>\
                                <td class="column-action">\
                                    <a href="#" title="Delete" class="delete" data-id="' + json.id_obat + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
                                    <input type="hidden" name="medicine_id[]" value="' + json.id_obat + '" class="medicine_id" />\
                                    <input type="hidden" name="medicine_state[]" value="add" class="medicine_state" />\
                                </td>\
                            </tr>';


                    $('#list-medicines tbody').append($html);

                    $('#medicine-code').val('').focus();
                    $('#medicine-id').val('');
                }else{
                    alert(json.message);
                }
            }
        });

    	return false
    });

	$('#list-medicines .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('#list-medicines tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus item ini dari list?');

		if($confirm){
			$item.find('.medicine_state').val('delete');
            $item.addClass('hide');

            $count_item = $('#list-medicines tr.item:not(.hide)').length;

            if($count_item < 1){
                $html = '<tr class="no-data">\
                            <td colspan="5">Tidak ada data yang ditemukan.</td>\
                        </tr>';

                $('#list-medicines tbody').append($html);
            }
		}

		return false;
	});
});
</script>	
@stop

@section( 'accident.scripts' )
<script type="text/javascript">
$(document).ready(function(){
    $('#accident-status .radio-button label').click(function(){
        var $this = $(this),
            $fa = $(this).find('i.fa'),
            $status = $(this).attr('data-status');

        if($fa.hasClass('fa-circle-thin')){
            $('#jenis-kecelakaan').val($status);
            $('#accident-status .radio-button label i.fa').removeClass('fa-dot-circle-o').addClass('fa-circle-thin');
            $fa.removeClass('fa-circle-thin').addClass('fa-dot-circle-o');
        }

        return false;
    });

    $('#datetime-accident').datetimepicker({
        dateFormat : 'yy-mm-dd',
        timeFormat: "HH:mm:ss",
    });
});
</script>
@stop