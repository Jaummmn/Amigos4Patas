$(document).ready(function() {

    function openUserContainer() {
        $(".user_header").click(function() {
            $(".conteudo-hover").removeClass("hidden");
        });
    
        
        $("body").click(function() {
            $(".conteudo-hover").addClass("hidden");
        });
    
       
        $(".user_header").click(function(event){
            event.stopPropagation();
        });
    }
    
    openUserContainer();
    
    
    
function openModal() {
      const open = $('.logIn');

      open.click(function(e) {
          e.stopPropagation();
          $('.bg').fadeIn();
      });
  }
  openModal();

  function closeModal() {
      const el = $('.btnClose, body');
      el.click(function() {
          $('.bg').fadeOut();
      });
      $('.form').click(function(e) {
          e.stopPropagation();
      });
  }
  closeModal();




   
  $('.menu').click(function(e){
    e.stopPropagation();
    
    $(".mobile_menu").removeClass('hidden');
    $(".bg_mobile_menu").fadeIn();
    $( ".mobile_menu" ).animate({width:'70%'});})

    const close =  $('.close,body');
        close.click(function close(){
            $(".bg_mobile_menu").fadeOut();
            $( ".mobile_menu" ).animate({width:'-80%'});                
            $(".mobile_menu").removeClass('hidden');
        });
    $('.mobile_menu').click(function(e){
        e.stopPropagation();
    });
            

    $('input[type="radio"]').change(function() {
        // Verifique qual botão de rádio está selecionado
        var selectedSection = $('input[name="user"]:checked').val();

        // Oculte todas as seções e mostre apenas a selecionada
        if(selectedSection == "canil") {
            $(".user_info").addClass("hidden");
            $(".canil_info").removeClass("hidden");
        }
        if(selectedSection == "adotante") {
            $(".canil_info").addClass("hidden");
            $(".user_info").removeClass("hidden");
        }
    }); 
    if(window.location.href.indexOf("https://amigos4patas.com/?pagina=pets") > -1) {
        var larguraPagina = $(window).width();
        var scrollTopValue = 200;

        if(larguraPagina <= 700){
            scrollTopValue = 900; 
        }

        $('html, body').animate({
            scrollTop: scrollTopValue
        }, 1000);
    }

  
    
  
  const inputFile = document.querySelector("#picture__input");
    const pictureImage = document.querySelector(".picture__image");
    const pictureImageTxt = "Escolha alguma Imagem";
    pictureImage.innerHTML = pictureImageTxt;
    
    inputFile.addEventListener("change", function (e) {
      const inputTarget = e.target;
      const file = inputTarget.files[0];
    
      if (file) {
        const reader = new FileReader();
    
        reader.addEventListener("load", function (e) {
          const readerTarget = e.target;
    
          const img = document.createElement("img");
          img.src = readerTarget.result;
          img.classList.add("picture__img");
    
          pictureImage.innerHTML = "";
          pictureImage.appendChild(img);
        });
    
        reader.readAsDataURL(file);
      } else {
        pictureImage.innerHTML = pictureImageTxt;
      }
    });
    
  document.getElementById('NumUsuario').addEventListener('input', function (e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
  
});
