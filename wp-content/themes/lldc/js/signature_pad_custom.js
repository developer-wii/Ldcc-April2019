    // signature js custom code here
    
    var canvas = document.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas);
    signaturePad.penColor = "rgb(66, 133, 244)";
    signaturePad.backgroundColor = "rgba(255,255,255,1)";
    canvas.width = 300;
    canvas.height = 100;
    
    
    function sign_pad_clear(){
        signaturePad.clear();
        signaturePad.on();
    }
    
    $(document).ready(function(){
        
        sign_pad_clear();
        
        $('form').submit(function(){
            
            if($('input[name="ak_recipet_check"]:checked').length > 0){
                $('input[name="ak_recipet"]').val( $('input[name="ak_recipet_check"]').val() );
                //return false;
            }
            else{
                alert('Pleack checked the I acknowledge receipt of items from LDCC');
                return false;
            }
            
            var img_data = signaturePad.toDataURL('image/png');
            $('.hidden_image64base').val(img_data);
            
            if($('input[name="d_w_sign_check"]:checked').length > 0 ){
                //alert($('input[name="d_w_sign_check"]').val());
                $('input[name="d_w_sign"]').val( $('input[name="d_w_sign_check"]').val() );
                
            }
            else{
                if(signaturePad.isEmpty()){
                    alert("Please provide a signature first.");
                    return false;    
                }
            }
           
            
            
        });
        
        $('.d_w_sign_check').click(function(){
            if($('input[name="d_w_sign_check"]:checked').length > 0){
                $('.sign_pad').css('display','none');
                $('.hidden_image64base').val("");
                sign_pad_clear();
            }
            else{
                $('.sign_pad').css('display','block');
            }
        });
        
        $('.sign_pad_clear').click(function(){
            sign_pad_clear();
            $('.hidden_image64base').val("");
        });
        
    });
            
            