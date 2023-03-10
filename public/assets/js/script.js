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

$('.structure-wrapper__empty-btn').click(function () {
	$('.structure-wrapper').css('display','none');
	$('.main-sidebar__blocks').css('display','flex');

	$('.main-sidebar__header-btn').each(function(){
		$(this).removeClass('main-sidebar__header-btn_active');
		let attr = $(this).attr('data-item');
		if(attr === 'blocks') {
			$(this).addClass('main-sidebar__header-btn_active');
		}
	});
})

$('.main-sidebar__block').click(function(){
	let nameBlock = $(this).attr('data-block');
	$('.main-sidebar__block-prop').each(function(){
		let attrBlock = $(this).attr('data-block');
		if(nameBlock === attrBlock){
			$('.main-sidebar__header').fadeOut(300);
			$('.main-sidebar__blocks').fadeOut(300);
			$('.main-sidebar-template').css('display','none');
			$(this).fadeIn(300)
		}
	})
});

$('.main-sidebar__block-prop-btn_decline_main').click(function(){
	$(this).parent().parent().fadeOut(300);
	$('.main-sidebar__header').css('display','flex');
	$('.main-sidebar__blocks').css('display','flex');
	$('.main-sidebar-template').css('display','block');
});

$(document).on('click','.main-sidebar__block-prop-select .main-sidebar__block-prop-input',function () {
	$(this).parent().find('.main-sidebar__block-prop-select-list').slideToggle();
})

$(document).on('click','.main-sidebar__block-prop-select-list li',function () {
	let dataText = $(this).html();
	$(this).parent().slideUp();
	$(this).parent().parent().find('.main-sidebar__block-prop-input input').val(dataText);
})

$(document).on('click','.main-sidebar__block-prop-btn_accept_add',function(){
	$(this).parent().parent().slideUp();
	let bannerBlock = $(this).parent().parent().find('.banner-img');
	if(bannerBlock.length) {
		let bannerImg = $(this).parent().parent().find('.banner-img').attr('src');

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
				console.log(bannerImg);
				bannerBlock.attr('src',bannerImg);
				getStructureJson();
			});
	} else {
		getStructureJson();
	}

})

$('.main-sidebar__block-prop-btn_accept-main').click(function(){
	let btnData = $(this).attr('data-btn');
	let imgIcon = '';
	let nameStructure = '';
	let structureBlock = '';

	if(btnData === 'header') {
		imgIcon = '/assets/images/icons/9.svg';
		nameStructure = 'Header';
		let siteName = $(this).parent().parent().find('.main-sidebar__block-prop-input input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(siteName,btnData);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'banner') {
		imgIcon = '/assets/images/icons/8.svg';
		nameStructure = '????????????';
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
			$(this).parent().parent().find('.banner-img').attr('src',bannerImg);
			getStructureJson();
		});


	}

	if(btnData === 'timer') {
		imgIcon = '/assets/images/icons/6.svg';
		nameStructure = '????????????';
		let timer = $(this).parent().parent().find('.main-sidebar__block-prop-input input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(timer,btnData);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'text') {
		imgIcon = '/assets/images/icons/7.svg';
		nameStructure = '??????????';
		let text = $(this).parent().parent().find('.main-sidebar__block-prop-input input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(text,btnData);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'single-product') {
		imgIcon = '/assets/images/icons/1.svg';
		nameStructure = '1 ??????????';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
		
	}

	if(btnData === 'two-product') {
		imgIcon = '/assets/images/icons/5.svg';
		nameStructure = '2 ????????????';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'two-product-1') {
		imgIcon = '/assets/images/icons/2.svg';
		nameStructure = '2 ????????????';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'two-product-2') {
		imgIcon = '/assets/images/icons/10.svg';
		nameStructure = '2 ????????????';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'three-product') {
		imgIcon = '/assets/images/icons/4.svg';
		nameStructure = '3 ????????????';
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let products = $(this).parent().parent().find('.main-sidebar__product');
		let productInfo = getProductData(products);
		let structureBlockBody = createStructureBlockBodyProducts(productInfo);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	if(btnData === 'footer') {
		imgIcon = '/assets/images/icons/3.svg';
		nameStructure = 'Footer';
		let siteName = $(this).parent().parent().find('.main-sidebar__block-prop-input_site input').val();
		let structureBlockHeader = createStructureHeader(imgIcon,nameStructure);
		let structureBlockBody = createStructureBlockBodyOther(siteName,btnData,null,null);
		structureBlock = createStructure(structureBlockHeader,structureBlockBody,btnData);
	}

	$(this).parent().parent().fadeOut(300);
	$('.main-sidebar__header').css('display','flex');
	$('.main-sidebar-template').css('display','block');
	$('.main-sidebar__header-btn').each(function(){
		let attr = $(this).attr('data-item');
		$(this).removeClass('main-sidebar__header-btn_active');
		if(attr === 'structure') {
			$(this).addClass('main-sidebar__header-btn_active');
		}
	});
	let structureWrapper = $('.structure-wrapper');
	$('.structure-wrapper__empty ').remove();
	structureWrapper.append(structureBlock);
	$('.structure-wrapper__save').remove();
	checkAndAddSaveBtns();
	structureWrapper.css('display','block');
	getStructureJson();

	//erase all inputs data
	let inputs = $(this).parent().parent().find('.main-sidebar__block-prop-input input');
	inputs.each(function () {
		$(this).val('');
	})

	$('.sale-block__wrapper').empty();
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
		productStructure.sale = prodSaleBlock;

		productsData.push(productStructure);
	});
	return productsData;
}

function createStructureHeader(imgIcon,nameStructure){
	return '<div class="structure-block-header"><div class="structure-block-header__name"><div class="structure-block-header__name-img"><img src="'+imgIcon+'" alt=""></div><p>'+nameStructure+'</p></div><img src="/assets/images/icons/arrow.svg" alt="" class="structure-arrow"></div>';
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
					salesStructure+= '<div class="sale-block__item"><div class="sale-block__item-unit"><label for="">???????????? ????????????</label><input type="text" placeholder="10%" value="'+sales[k].text+'"></div><div class="sale-block__item-unit"><label for="">???????? HEX</label><input type="text" placeholder="#ff0000" value="'+sales[k].color+'"></div><img src="/assets/images/icons/cross.svg" alt="" class="remove-sale"></div>'
				}
			}

			productsStructure+= '<div class="main-sidebar__product"><div class="main-sidebar__product-wrapper"><div class="main-sidebar__block-prop-input"><label for="">???????????? ???? ??????????</label><input type="text" placeholder="???????????? ???? ??????????" value="'+link+'"></div></div><div class="sale-block"><h4>????????????</h4><div class="sale-block__wrapper">'+salesStructure+'</div><div class="sale-block__btn"><p>???????????????? ????????????</p></div></div></div>';
		}
	}
	

	let structureBtns = '<div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';

	structureBlock = productsStructure+structureBtns;
	return structureBlock;
}


function createStructureBlockBodyOther(data,btnData,alt = null,link = null,delivery=null,discount=null){
	if(btnData === 'header') {
		return '<div class="main-sidebar__block-prop-select"><div class="main-sidebar__block-prop-input"><label for="">????????</label><input type="text" value="'+data+'" readonly><img src="/assets/images/icons/triangle.svg" alt=""></div><ul class="main-sidebar__block-prop-select-list"><li>Joseph Kitchen</li><li>Umbra Shop</li><li>Mason Cash</li><li>Reisenthel</li><li>Monbento</li><li>Guzzini</li><li>Liberty Jones</li><li>Smart Solutions</li><li>Bergenson Bjorn</li><li>Silikomart</li><li>Wildtoys</li><li>SCHLEICH</li><li>Djeco</li><li>SafariToys</li><li>Typhoon</li><li>Likelunch</li><li>PaolaReina</li><li>Britov</li></ul></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'banner') {
		return '<form action="" method="post" enctype="multipart/form-data"><label class="input-file"><div class="input-file-block"><img src="'+data+'" alt="" class="banner-img" style="width: 75%; height: 75%;"><input type="file" name="file[]" accept="image/*"></div></label></form><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-link"><label for="">????????????</label><input type="text" placeholder="????????????" value="'+link+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-alt"><label for="">Alt ??????????</label><input type="text" placeholder="Alt ??????????" value="'+alt+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'banner-common') {
		return '<form action="" method="post" enctype="multipart/form-data"><label class="input-file"><div class="input-file-block"><img src="'+data+'" alt="" class="banner-img" style="width: 75%; height: 75%;"><input type="file" name="file[]" accept="image/*"></div></label></form><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-link"><label for="">????????????</label><input type="text" placeholder="????????????" value="'+link+'"></div><div class="main-sidebar__block-prop-input main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_margin main-sidebar__block-prop-input_banner-alt"><label for="">Alt ??????????</label><input type="text" placeholder="Alt ??????????" value="'+alt+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'timer') {
		return '<div class="main-sidebar__block-prop-input"><label for="">?????????? ???????????????? ??????????????</label><input type="text" value="'+data+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'text') {
		return '<div class="main-sidebar__block-prop-input"><label for="">???????????????????? ???????????????????? ??????????</label><input type="text" value="'+data+'"></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';
	}

	if(btnData === 'footer') {
		return '<div class="main-sidebar__block-prop-select"><div class="main-sidebar__block-prop-input"><label for="">????????</label><input type="text" value="'+data+'" readonly><img src="/assets/images/icons/triangle.svg" alt=""></div><ul class="main-sidebar__block-prop-select-list"><li>Joseph Kitchen</li><li>Umbra Shop</li><li>Mason Cash</li><li>Reisenthel</li><li>Monbento</li><li>Guzzini</li><li>Liberty Jones</li><li>Smart Solutions</li><li>Bergenson Bjorn</li><li>Silikomart</li><li>Wildtoys</li><li>SCHLEICH</li><li>Djeco</li><li>SafariToys</li><li>Typhoon</li><li>Likelunch</li><li>PaolaReina</li><li>Britov</li></ul></div><div class="main-sidebar__block-prop-btns"><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_accept main-sidebar__block-prop-btn_accept_add"><img src="/assets/images/icons/save.svg" alt=""></div><div class="main-sidebar__block-prop-btn main-sidebar__block-prop-btn_decline main-sidebar__block-prop-btn_decline_add"><img src="/assets/images/icons/cross.svg" alt=""></div></div>';
	}
}

function createStructure(structureBlockHeader,structureBlockBody,btnData){
	return '<div class="structure-block" data-structure="'+btnData+'">'+structureBlockHeader+'<div class="structure-block-body">'+structureBlockBody+'</div></div>';
}

$(document).on('click','.sale-block__btn',function(){
	let saleBlock = '<div class="sale-block__item"><div class="sale-block__item-unit"><label for="">???????????? ????????????</label><input type="text" placeholder="10%"></div><div class="sale-block__item-unit"><label for="">???????? HEX</label><input type="text" placeholder="#ff0000"></div><img src="/assets/images/icons/cross.svg" alt="" class="remove-sale"></div>';

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
			let structureItem = {
				blockName: structureName,
				siteName: siteName,
			}
			structureArr.push(structureItem);
		}
	});

	//add general settings for json
	let settingsArr = {};
	settingsArr.blockName = 'settings';
	settingsArr.saleColor1 = $('#sale-color-1').val();
	settingsArr.saleColor2 = $('#sale-color-2').val();
	settingsArr.couponName = $('#coupon-name').val();
	settingsArr.salePercent = $('#sale-percent').val();
	settingsArr.mailTheme = $('#mail-theme').val();
	settingsArr.hideTheme = $('#mail-hide-theme').val();
	let ignoreSite = false;
	if ($('#ignore-site-sale').is(':checked')){
		ignoreSite = true;
	}
	settingsArr.ignoreSite = ignoreSite;

	structureArr.push(settingsArr);

	console.log(structureArr);

	let json = JSON.stringify(structureArr);

	let url = window.location.pathname;
	let urlArr = url.split('/');
	let pathname = urlArr[urlArr.length-2];
	if(pathname === 'template') {
		let id = urlArr[urlArr.length-1];
		let btns = $('.structure-wrapper__save');
		if(!btns.length) {
			let btnBlock = '<div class="structure-wrapper__save"><button class="structure-wrapper__save-update">???????????????? ????????????</button><button class="structure-wrapper__save-copy">???????????????????? ????????????</button><button class="structure-wrapper__save-delete" data-id="'+id+'">?????????????? ????????????</button></div>';
			$('.structure-wrapper').append(btnBlock);
		}
	}
	
	$.ajax({
		url: '/generateMail',
		method: 'post',
		dataType: 'html',
		data: {'json': json},
		success: function(data){
			let copyBlock = $('#copy-block');
			let renderMailBlock = $('.main-render__mail-block');
			$('.main-render__mail-block-empty').remove();
			renderMailBlock.empty();
			renderMailBlock.append(data);
			copyBlock.val('');
			copyBlock.val(data);
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
	alert('?????????????? ??????????????????????!');
})

$('.send-auth').click(function(){
	let user = $('#name').val();
	let pass = $('#pass').val();

	$.ajax({
		url: '/authData',
		method: 'post',
		dataType: 'html',
		data: {
			'name': user,
			'pass': pass
		},
		success: function(data){
			let json = $.parseJSON(data);
			let answer = json.result;
			if(answer === 'AuthSuccess') {
				window.location.href = '/';
			} else {
				let errBlock = $('.err-text');
				errBlock.css('display','block');
				errBlock.html(answer);
			}
		}
	});
});

function checkAndAddSaveBtns() {
	let url = window.location.pathname;
	let btnsBlock = '';
	if(url === '/constructor') {
		btnsBlock = '<div class="structure-wrapper__save"><button class="structure-wrapper__save-btn">?????????????????? ????????????</button></div>';
		$('.structure-wrapper').append(btnsBlock);
	}
	//?????????????????? ?????? ???????????? ?? ?????????????????????? ??????????????
	else {
		let urlArr = url.split('/');
		let blockState = false;
		btnsBlock = '<div class="structure-wrapper__save"><button class="structure-wrapper__save-btn">?????????????????? ????????????</button><button class="structure-wrapper__delete-btn">?????????????? ????????????</button></div>';
		for(let i = 0; i < urlArr.length; i++) {
			if(urlArr[i] === 'template') {
				blockState = true;
			}
		}
	}
}

$(document).on('click','.structure-wrapper__save-btn',function () {
	$('.main-sidebar__header').fadeOut(300);
	$('.structure-wrapper').fadeOut(300);
	$('.main-sidebar-template').css('display','none');
	$('.main-sidebar__header-btn').each(function(){
		$(this).removeClass('main-sidebar__header-btn_active');
		let attr = $(this).attr('data-item');
		if(attr === 'blocks') {
			$(this).addClass('main-sidebar__header-btn_active');
		}
	});
	$('.main-sidebar__save-template-block').css('display','block');
})

function getStructureJsonToSave() {
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
			let structureItem = {
				blockName: structureName,
				siteName: siteName,
			}
			structureArr.push(structureItem);
		}
	});

	//add general settings for json
	let settingsArr = {};
	settingsArr.blockName = 'settings';
	settingsArr.saleColor1 = $('#sale-color-1').val();
	settingsArr.saleColor2 = $('#sale-color-2').val();
	settingsArr.couponName = $('#coupon-name').val();
	settingsArr.salePercent = $('#sale-percent').val();
	settingsArr.mailTheme = $('#mail-theme').val();
	settingsArr.hideTheme = $('#mail-hide-theme').val();

	let ignoreSite = false;
	if ($('#ignore-site-sale').is(':checked')){
		ignoreSite = true;
	}
	settingsArr.ignoreSite = ignoreSite;

	structureArr.push(settingsArr);

	return JSON.stringify(structureArr);
}

$('.main-sidebar__block-prop-btn_accept-save').click(function () {
	let name = $(this).parent().parent().find('input').val();
	let json = getStructureJsonToSave();
	let html = $('#copy-block').val();
	$('.await-window').fadeIn(300);

	$.ajax({
		url: '/createTemplate',
		method: 'post',
		dataType: 'html',
		data: {
			'name' : name,
			'json': json,
			'html' : html
		},
		success: function(data){
			$(location).attr('href','/templates');
		}
	});
});

$('.main-sidebar-settings-btn').click(function () {
	$('.main-sidebar__header').fadeOut(300);
	$('.main-sidebar__blocks').fadeOut(300);
	$('.main-sidebar-template').css('display','none');
	$('.main-sidebar__general-settings').fadeIn();
});

$('.save-settings').click(function(){
	$(this).parent().parent().fadeOut(300);
	$('.main-sidebar__header').css('display','flex');
	$('.main-sidebar__blocks').css('display','flex');
	$('.main-sidebar-template').css('display','block');
	getStructureJson();
});

$(document).on('click','.structure-wrapper__save-update',function () {
	$('.main-sidebar__save-template-block').each(function () {
		let blockName = $(this).attr('data-block');
		if(blockName === 'update-template') {
			$('.main-sidebar__header').fadeOut(300);
			$('.structure-wrapper').fadeOut(300);
			$('.main-sidebar-template').css('display','none');
			$('.main-sidebar__header-btn').each(function(){
				$(this).removeClass('main-sidebar__header-btn_active');
				let attr = $(this).attr('data-item');
				if(attr === 'blocks') {
					$(this).addClass('main-sidebar__header-btn_active');
				}
			});
			$(this).css('display','block');
		}
	});
});


$(document).on('click','.structure-wrapper__save-copy',function () {
	$('.main-sidebar__save-template-block').each(function () {
		let blockName = $(this).attr('data-block');
		if(blockName === 'copy-template') {
			$('.main-sidebar__header').fadeOut(300);
			$('.structure-wrapper').fadeOut(300);
			$('.main-sidebar-template').css('display','none');
			$('.main-sidebar__header-btn').each(function(){
				$(this).removeClass('main-sidebar__header-btn_active');
				let attr = $(this).attr('data-item');
				if(attr === 'blocks') {
					$(this).addClass('main-sidebar__header-btn_active');
				}
			});
			$(this).css('display','block');
		}
	});
})


$('.main-sidebar__block-prop-btn_accept-update').click(function () {
	let name = $(this).parent().parent().find('input').val();
	let json = getStructureJsonToSave();
	let id = $(this).attr('data-btn');
	let html = $('#copy-block').val();
	$('.await-window').fadeIn(300);

	$.ajax({
		url: '/updateTemplate',
		method: 'post',
		dataType: 'html',
		data: {
			'id' : id,
			'name' : name,
			'json': json,
			'html' : html
		},
		success: function(data){
			$('.await-window').fadeOut(300);
			$(this).parent().parent().find('input').val(name);
			$('.main-sidebar__save-template-block').each(function () {
				$(this).css('display','none');
			});
			$('.main-sidebar__header').css('display','flex');
			$('.main-sidebar__blocks').css('display','flex');
			$('.main-sidebar-template').css('display','block');
		}
	});
});

$(document).on('click','.structure-wrapper__save-delete',function () {
	let id = $(this).attr('data-id');

	$.ajax({
		url: '/deleteTemplate',
		method: 'post',
		dataType: 'html',
		data: {
			'id' : id,
		},
		success: function(data){
			$(location).attr('href','/templates');
		}
	});
})

$('.main-sidebar-reset-btn').click(function () {
	$.ajax({
		url: '/clearSession',
		method: 'post',
		dataType: 'html',
		data: {},
		success: function(data){
			getStructureJson();
		}
	});
})

$('.create-zip__side').click(function () {
	let id = $(this).attr('data-id');
	let name = $(this).attr('data-name');

	$.ajax({
		url: '/clearSession',
		method: 'post',
		dataType: 'html',
		data: {},
		success: function (data){
			let json = getStructureJsonToSave();
			$.ajax({
				url: '/generateMail',
				method: 'post',
				dataType: 'html',
				data: {'json': json},
				success: function(data){
					$.ajax({
						url: '/updateTemplate',
						method: 'post',
						dataType: 'html',
						data: {
							'id' : id,
							'name' : name,
							'json': json,
							'html' : data
						},
						success: function(data){
							let url = '/templates/zip/'+id;
							$(location).attr('href',url);
						}
					})
				}
			})

		}
	})
})