let dt = new DataTransfer();

$('.input-file input[type=file]').on('change', function(){
    let fileblock = $(this).closest('.input-file');
    let imgBlock = fileblock.find('.banner-img');
	for(let i = 0; i < this.files.length; i++){
		let file = this.files.item(i);
		dt.items.add(file);    
   
		let reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onloadend = function(){
            fileblock.find('span').css('display','none');
            imgBlock.css('width','75%');
            imgBlock.css('height','75%');
            imgBlock.attr('src',reader.result);
		}
	};
	this.files = dt.files;
});

$(document).on('click','.structure-block-header',function(){
	let bodyState = $(this).parent().find('.structure-block-body').css('display');
	$(this).parent().find('.structure-block-body').slideToggle();
	if(bodyState === 'none'){
		$(this).find('.structure-arrow').addClass('structure-arrow_rotate');
	} else {
		$(this).find('.structure-arrow').removeClass('structure-arrow_rotate');
	}
});

$('.structure-wrapper').sortable({
	update : function () {
		getStructureJson();
	}
});

$('.main-sidebar__header-btn').click(function(){
	let attr = $(this).attr('data-item');

	$('.main-sidebar__header-btn').each(function(){
		$(this).removeClass('main-sidebar__header-btn_active');
	});

	if(attr === 'blocks') {
		$('.structure-wrapper').css('display','none');
		$('.main-sidebar__blocks').css('display','flex');
	}

	if(attr === 'structure') {
		$('.structure-wrapper').css('display','block');
		$('.main-sidebar__blocks').css('display','none');
	}

	$(this).addClass('main-sidebar__header-btn_active');
});

$('.main-sidebar__block').click(function(){
	let nameBlock = $(this).attr('data-block');
	$('.main-sidebar__block-prop').each(function(){
		let attrBlock = $(this).attr('data-block');
		if(nameBlock === attrBlock){
			$('.main-sidebar__header').fadeOut(300);
			$('.main-sidebar__blocks').fadeOut(300);
			$(this).fadeIn(300)
		}
	})
});

$('.main-sidebar__block-prop-btn_decline_main').click(function(){
	$(this).parent().parent().fadeOut(300);
	$('.main-sidebar__header').css('display','flex');
	$('.main-sidebar__blocks').css('display','flex');
});

$('.main-sidebar__block-prop-select .main-sidebar__block-prop-input').click(function(){
	$(this).parent().find('.main-sidebar__block-prop-select-list').slideToggle();
});

$('.main-sidebar__block-prop-select-list li').click(function(){
	let dataText = $(this).html();
	$(this).parent().slideUp();
	$(this).parent().parent().find('.main-sidebar__block-prop-input input').val(dataText);
})

$(document).on('click','.main-sidebar__block-prop-btn_accept_add',function(){
	$(this).parent().parent().slideUp();
})

$('.main-sidebar__block-prop-btn_accept-main').click(function(){
	let btnData = $(this).attr('data-btn');
	let imgIcon = '';
	let nameStructure = '';
	let structureBlock = '';

	if(btnData === 'header') {
		imgIcon = 'assets/images/icons/9.svg';
		nameStructure = 'Header';
		let siteName = $(this).parent().parent().find('.main-sidebar__block-prop-input input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(siteName,btnData);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'banner') {
		imgIcon = 'assets/images/icons/8.svg';
		nameStructure = 'Баннер';
		let bannerImg = $(this).parent().parent().find('.banner-img').attr('src');
		let link = $(this).parent().parent().find('.main-sidebar__block-prop-input_banner-link input').val();
		let alt = $(this).parent().parent().find('.main-sidebar__block-prop-input_banner-alt input').val();

		//upload data to server
		let url = "/uploadImage";
		let base64ImageContent = bannerImg.replace(/^data:image\/(png|jpg);base64,/, "");
		base64ImageContent = base64ImageContent.replace(/^data:image\/jpeg;base64,/, "");
		let blob = base64ToBlob(base64ImageContent, 'image/png');
		let formData = new FormData();
		formData.append('userFile', blob);
		$.ajax({
			url: url,
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			data: formData
		})
		.done(function(e){
			let jsonObj = $.parseJSON(e);
			let bannerImg = jsonObj.fileLink;
			let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
			let structureBlockBody = createStructureBlockBodyOther(bannerImg,btnData,alt,link);
			structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
			let structureWrapper = $('.structure-wrapper');
			structureWrapper.append(structureBlock);
			structureWrapper.css('display','block');
			getStructureJson();
		});


	}

	if(btnData === 'banner-common') {
		imgIcon = 'assets/images/icons/8.svg';
		nameStructure = 'Баннер - Общий';
		let bannerImg = $(this).parent().parent().find('.banner-img').attr('src');
		let link = $(this).parent().parent().find('.main-sidebar__block-prop-input_banner-link input').val();
		let alt = $(this).parent().parent().find('.main-sidebar__block-prop-input_banner-alt input').val();

		//upload data to server
		let url = "/uploadImage";
		let base64ImageContent = bannerImg.replace(/^data:image\/(png|jpg);base64,/, "");
		base64ImageContent = base64ImageContent.replace(/^data:image\/jpeg;base64,/, "");
		let blob = base64ToBlob(base64ImageContent, 'image/png');
		let formData = new FormData();
		formData.append('userFile', blob);
		$.ajax({
			url: url,
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			data: formData
		})
			.done(function(e){
				let jsonObj = $.parseJSON(e);
				let bannerImg = jsonObj.fileLink;
				let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
				let structureBlockBody = createStructureBlockBodyOther(bannerImg,btnData,alt,link);
				structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
				let structureWrapper = $('.structure-wrapper');
				structureWrapper.append(structureBlock);
				structureWrapper.css('display','block');
				getStructureJson();
			});


	}


	if(btnData === 'timer') {
		imgIcon = 'assets/images/icons/6.svg';
		nameStructure = 'Таймер';
		let timer = $(this).parent().parent().find('.main-sidebar__block-prop-input input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(timer,btnData);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'text') {
		imgIcon = 'assets/images/icons/7.svg';
		nameStructure = 'Текст';
		let text = $(this).parent().parent().find('.main-sidebar__block-prop-input input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(text,btnData);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'single-product') {
		imgIcon = 'assets/images/icons/1.svg';
		nameStructure = '1 товар';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
		
	}

	if(btnData === 'two-product') {
		imgIcon = 'assets/images/icons/5.svg';
		nameStructure = '2 товара';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'two-product-1') {
		imgIcon = 'assets/images/icons/2.svg';
		nameStructure = '2 товара';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'two-product-2') {
		imgIcon = 'assets/images/icons/10.svg';
		nameStructure = '2 товара';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'three-product') {
		imgIcon = 'assets/images/icons/4.svg';
		nameStructure = '3 товара';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'footer') {
		imgIcon = 'assets/images/icons/3.svg';
		nameStructure = 'Footer';
		let siteName = $(this).parent().parent().find('.main-sidebar__block-prop-input_site input').val();
		let delivery = $(this).parent().parent().find('.main-sidebar__block-prop-input_delivery input').val();
		let discount = $(this).parent().parent().find('.main-sidebar__block-prop-input_discount input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(siteName,btnData,null,null,delivery,discount);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	$(this).parent().parent().fadeOut(300);
	$('.main-sidebar__header').css('display','flex');
	$('.main-sidebar__header-btn').each(function(){
		let attr = $(this).attr('data-item');
		$(this).removeClass('main-sidebar__header-btn_active');
		if(attr === 'structure') {
			$(this).addClass('main-sidebar__header-btn_active');
		}
	});
	let structureWrapper = $('.structure-wrapper');
	structureWrapper.append(structureBlock);
	structureWrapper.css('display','block');
	getStructureJson();
});


function base64ToBlob(base64, mime) 
{
    mime = mime || '';
    let sliceSize = 1024;
    let byteChars = window.atob(base64);
    let byteArrays = [];

    for (let offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
        let slice = byteChars.slice(offset, offset + sliceSize);

        let byteNumbers = new Array(slice.length);
        for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        let byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: mime});
}

function getProductData(products) {
	let productsData = [];
	products.each(function(){
		let prodInfo = [];
		let prodSaleBlock = [];
		let productStructure = {};

		//All products info
		$(this).find('.main-sidebar__product-wrapper .main-sidebar__block-prop-input input').each(function(){
			let prodVal = $(this).val();
			prodInfo.push(prodVal);
		})

		//All sales product info
		$(this).find('.sale-block .sale-block__wrapper .sale-block__item').each(function(){
			let prodSales = [];
			$(this).find('.sale-block__item-unit input').each(function(){
				let prodSale = $(this).val();
				prodSales.push(prodSale)
			})
			let prodSalesObj = {
				text: prodSales[0],
				color: prodSales[1],
			}
			prodSaleBlock.push(prodSalesObj);
		});

		productStructure.link = prodInfo[0];
		productStructure.price = prodInfo[1];
		productStructure.newPrice = prodInfo[2];
		productStructure.sale = prodSaleBlock;

		productsData.push(productStructure);
	});
	return productsData;
}

function createStructureHeader(imgIcon,nameStructure){
	return '<div class="structure-block-header"><div class="structure-block-header__name"><div class="structure-block-header__name-img"><img src="'+imgIcon+'" alt=""></div><p>'+nameStructure+'</p></div><img src="assets/images/icons/arrow.svg" alt="" class="structure-arrow"></div>';
}

function createStructureBlockBodyProducts(productInfo) {
	let structureBlock = '';
	let productsStructure = '';

	if(productInfo.length > 0) {
		for(let i = 0; i< productInfo.length; i++) {
			let productData = productInfo[i];

			let link = productData.link;
			let newPrice = productData.newPrice;
			let price = productData.price;

			let sales = productData.sale;

			let salesStructure = '';

			if(sales.length > 0) {
				for(let k = 0; k < sales.length; k++) {
					salesStructure+= '<div class="sale-block__item"><div class="sale-block__item-unit"><label for="">Размер скидки</label><input type="text" placeholder="10%" value="'+sales[k].text+'"></div><div class="sale-block__item-unit"><label for="">Цвет HEX</label><input type="text" placeholder="#ff0000" value="'+sales[k].color+'"></div><img src="assets/images/icons/cross.svg" alt="" class="remove-sale"></div>'
				}
			}

			productsStructure+= '<div class="main-sidebar__product"><div class="main-sidebar__product-wrapper"><div class="main-sidebar__block-prop-input"><label for="">Ссылка на товар</label><input type="text" placeholder="Ссылка на товар" value="'+link+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin"><label for="">Текущая цена</label><input type="text" placeholder="Текущая цена" value="'+price+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin"><label for="">Новая цена</label><input type="text" placeholder="Новая цена" value="'+newPrice+'"></div></div><div class="sale-block"><h4>Скидки</h4><div class="sale-block__wrapper">'+salesStructure+'</div><div class="sale-block__btn"><p>Добавить скидку</p></div></div></div>';
		}
	}
	

	let structureBtns = '<div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';

	structureBlock = productsStructure+structureBtns;
	return structureBlock;
}


function createStructureBlockBodyOther(data,btnData,alt = null,link = null,delivery=null,discount=null){
	if(btnData === 'header') {
		return '<div class="main-sidebar__block-prop-select"><div class="main-sidebar__block-prop-input"><label for="">Сайт</label><input type="text" value="'+data+'" readonly><img src="assets/images/icons/triangle.svg" alt=""></div><ul class="main-sidebar__block-prop-select-list"><li>Monbento</li><li>Mason Cash</li><li>Paola Reinas</li></ul></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'banner') {
		return '<form action="" method="post" enctype="multipart/form-data"><label class="input-file"><div class="input-file-block"><img src="'+data+'" alt="" class="banner-img" style="width: 75%; height: 75%;"><input type="file" name="file[]" accept="image/*"></div></label></form><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-link"><label for="">Ссылка</label><input type="text" placeholder="Ссылка" value="'+link+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-alt"><label for="">Alt текст</label><input type="text" placeholder="Alt текст" value="'+alt+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'banner-common') {
		return '<form action="" method="post" enctype="multipart/form-data"><label class="input-file"><div class="input-file-block"><img src="'+data+'" alt="" class="banner-img" style="width: 75%; height: 75%;"><input type="file" name="file[]" accept="image/*"></div></label></form><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-link"><label for="">Ссылка</label><input type="text" placeholder="Ссылка" value="'+link+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-alt"><label for="">Alt текст</label><input type="text" placeholder="Alt текст" value="'+alt+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'timer') {
		return '<div class="main-sidebar__block-prop-input"><label for="">Время действия таймера</label><input type="text" value="'+data+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'text') {
		return '<div class="main-sidebar__block-prop-input"><label for="">Содержимое текстового блока</label><input type="text" value="'+data+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'footer') {
		return '<div class="main-sidebar__block-prop-select"><div class="main-sidebar__block-prop-input"><label for="">Сайт</label><input type="text" value="'+data+'" readonly><img src="assets/images/icons/triangle.svg" alt=""></div><ul class="main-sidebar__block-prop-select-list"><li>Monbento</li><li>Mason Cash</li><li>Paola Reinas</li></ul></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_delivery"><label for="">Ссылка на доставку</label><input type="text" placeholder="Ссылка на доставку" value="'+delivery+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_discount"><label for="">Ссылка на скидку</label><input type="text" placeholder="Ссылка на скидку" value="'+discount+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="assets/images/icons/cross.svg" alt=""></div></div>';
	}
}

function createStructure(structureBlockHeader,structureBlockBody,btnData){
	return '<div class="structure-block" data-structure="'+btnData+'">'+structureBlockHeader+'<div class="structure-block-body">'+structureBlockBody+'</div></div>';
}

$(document).on('click','.sale-block__btn',function(){
	let saleBlock = '<div class="sale-block__item"><div class="sale-block__item-unit"><label for="">Размер скидки</label><input type="text" placeholder="10%"></div><div class="sale-block__item-unit"><label for="">Цвет HEX</label><input type="text" placeholder="#ff0000"></div><img src="assets/images/icons/cross.svg" alt="" class="remove-sale"></div>';

	$(this).parent().find('.sale-block__wrapper').append(saleBlock);
});


$(document).on('click','.remove-sale',function(){
	$(this).parent().remove();
})

$(document).on('click','.main-sidebar__block-prop-btn_decline_add',function(){
	$(this).parent().parent().parent().remove();
	getStructureJson();
})

function getStructureJson() {
	let structureArr = [];
	$('.structure-block').each(function(){
		let structureName = $(this).attr('data-structure');
		
		if(structureName === 'header') {
			let siteName = $(this).find('.structure-block-body .main-sidebar__block-prop-select input').val();
			let structureItem = {
				blockName: structureName,
				siteName: siteName
			}
			structureArr.push(structureItem);
		}


		if(structureName === 'banner') {
			let img = $(this).find('.banner-img').attr('src');
			let link = $(this).find('.main-sidebar__block-prop-input_banner-link input').val();
			let alt = $(this).find('.main-sidebar__block-prop-input_banner-alt input').val();
			let structureItem = {
				blockName: structureName,
				img: img,
				link: link,
				alt: alt
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'banner-common') {
			let img = $(this).find('.banner-img').attr('src');
			let link = $(this).find('.main-sidebar__block-prop-input_banner-link input').val();
			let alt = $(this).find('.main-sidebar__block-prop-input_banner-alt input').val();
			let structureItem = {
				blockName: structureName,
				img: img,
				link: link,
				alt: alt
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'timer') {
			let timer = $(this).find('.structure-block-body .main-sidebar__block-prop-input input').val();
			let structureItem = {
				blockName: structureName,
				timer: timer
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'text') {
			let text = $(this).find('.structure-block-body .main-sidebar__block-prop-input input').val();
			let structureItem = {
				blockName: structureName,
				text: text
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'single-product') {
			let products = $(this).find('.structure-block-body .main-sidebar__product');
			let productsInfo = getProductData(products);
			let structureItem = {
				blockName: structureName,
				products: productsInfo
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'two-product') {
			let products = $(this).find('.structure-block-body .main-sidebar__product');
			let productsInfo = getProductData(products);
			let structureItem = {
				blockName: structureName,
				products: productsInfo
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'two-product-1') {
			let products = $(this).find('.structure-block-body .main-sidebar__product');
			let productsInfo = getProductData(products);
			let structureItem = {
				blockName: structureName,
				products: productsInfo
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'two-product-2') {
			let products = $(this).find('.structure-block-body .main-sidebar__product');
			let productsInfo = getProductData(products);
			let structureItem = {
				blockName: structureName,
				products: productsInfo
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'three-product') {
			let products = $(this).find('.structure-block-body .main-sidebar__product');
			let productsInfo = getProductData(products);
			let structureItem = {
				blockName: structureName,
				products: productsInfo
			}
			structureArr.push(structureItem);
		}

		if(structureName === 'footer') {
			let siteName = $(this).find('.structure-block-body .main-sidebar__block-prop-select input').val();
			let delivery = $(this).find('.main-sidebar__block-prop-input_delivery input').val();
			let discount = $(this).find('.main-sidebar__block-prop-input_discount input').val();
			let structureItem = {
				blockName: structureName,
				siteName: siteName,
				delivery: delivery,
				discount: discount
			}
			structureArr.push(structureItem);
		}
	});

	console.log(structureArr);

	let json = JSON.stringify(structureArr);
	
	$.ajax({
		url: '/generateMail',
		method: 'post',
		dataType: 'html',
		data: {'json': json},
		success: function(data){
			$('.main-render__mail-block-empty').remove();
			$('.main-render__mail-block').empty();
			$('.main-render__mail-block').append(data);
			$('#copy-block').val('');
			$('#copy-block').val(data);
		}
	});
}


$('.code-copy').click(function(){
	let copyText = $('#copy-block').val();
	let copyText2 = document.createElement('textarea');
	copyText2.value = copyText;
	document.body.appendChild(copyText2);
	copyText2.select();
	document.execCommand("copy");
	document.body.removeChild(copyText2);
	alert('Успешно скопировано!');
})