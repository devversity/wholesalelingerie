function cc_m2_c2a(){
	/**
	 * wait for form to exist before continuing
	 * (needed on sites that load page elements
	 * via multiple ajax requests)
	 */
	if (jQuery('[name="postcode"]').length == 0) {
		return;
	}
	
	jQuery('[name="postcode"]').each(function(index,elem){
		if(jQuery(elem).data('cc_attach') != '1'){
			jQuery(elem).data('cc_attach','1');
			var form = jQuery(elem).closest('form');

			var custom_id = '';
			if(c2a_config.advanced.search_elem_id !== null){
				custom_id = ' id="'+ c2a_config.advanced.search_elem_id +'"'
			}

			// null fix for m2_1.1.16
			if (c2a_config.texts.search_label == null) c2a_config.texts.search_label = '';

			var tmp_html = '<div class="field"'+custom_id+'><label class="label">' +
							c2a_config.texts.search_label+'</label>' +
							'<div class="control"><input class="cc_search_input" type="text"/></div></div>';
			if(c2a_config.advanced.hide_fields){
				var svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 305.67 179.25">'+
							'<rect x="-22.85" y="66.4" width="226.32" height="47.53" rx="17.33" ry="17.33" transform="translate(89.52 -37.99) rotate(45)"/>'+
							'<rect x="103.58" y="66.4" width="226.32" height="47.53" rx="17.33" ry="17.33" transform="translate(433.06 0.12) rotate(135)"/>'+
						'</svg>';
				tmp_html += '<div class="field cc_hide_fields_action"><label>'+c2a_config.texts.manual_entry_toggle+'</label>'+svg+'</div>';
			}
			if (!c2a_config.advanced.use_first_line || c2a_config.advanced.hide_fields) {
				form.find('#street_1').closest('div.street').before( tmp_html );
			} else {
				form.find('#street_1').addClass('cc_search_input');
			}
			if (c2a_config.advanced.lock_country_to_dropdown) {
					form.find('.cc_search_input').closest('div.field').before(form.find('[name="country_id"]').closest('div.field'));
			}

			var dom = {
				search:		form.find('.cc_search_input'),
				company:	form.find('[name="company"]'),
				line_1:		form.find('#street_1'),
				line_2:		form.find('#street_2'),
				postcode:	form.find('[name="postcode"]'),
				town:		form.find('[name="city"]'),
				county:		{
							input:	form.find('[name="region"]'),
							list:	form.find('[name="region_id"]')
				},
				country:	form.find('[name="country_id"]')
			};
			cc_holder.attach({
				search:		dom.search[0],
				company:	dom.company[0],
				line_1:		dom.line_1[0],
				line_2:		dom.line_2[0],
				postcode:	dom.postcode[0],
				town:		dom.town[0],
				county:		{
					input:	dom.county.input,
					list:	dom.county.list
				},
				country:	dom.country[0]
			});
			form.find('.cc_hide_fields_action').on('click',function(){
				cc_hide_fields(dom, 'manual-show')
			});

			cc_hide_fields(dom,'init');
		}
	});
}
var cc_holder = null;

function cc_hide_fields(dom, action){
	var action = action || 'show';
	if(!c2a_config.advanced.hide_fields){
		return;
	}
	switch(action){
		case 'init':
			var elementsToHide = ['line_1', 'line_2', 'line_3', 'line_4', 'town', 'postcode', 'county'];
			// determine if we can hide by default
			var formEmpty = true;
			for(var i=0; i<elementsToHide.length - 1; i++){ // -1 is to skip County
				if(jQuery(dom[elementsToHide[i]]).length && jQuery(dom[elementsToHide[i]]).val() !== ''){
					formEmpty = false;
				}
			}
			if(!c2a_config.advanced.lock_country_to_dropdown){
				elementsToHide.push('country');
			}
			for(var i=0; i<elementsToHide.length; i++){
				if(jQuery(dom[elementsToHide[i]]).length){
					switch(elementsToHide[i]){
						case 'county':
							jQuery(dom[elementsToHide[i]].input).closest('.field').addClass('cc_hide');
							jQuery(dom[elementsToHide[i]].list).closest('.field').addClass('cc_hide');
							break;
						case 'line_1':
							jQuery(dom[elementsToHide[i]]).closest('div.street').addClass('cc_hide');
							break;
						default:
							jQuery(dom[elementsToHide[i]]).closest('.field').addClass('cc_hide');
					}
				}
			}
			var form = jQuery(dom.country).closest('form');
			// store the checking loop in the DOM object
			form.data('cc_hidden',0);
			if(formEmpty){
				cc_hide_fields(dom, 'hide');
			} else {
				cc_hide_fields(dom, 'show');
			}
			setInterval(function(){cc_reveal_fields_on_error(dom);}, 250);
			break;
		case 'hide':
			var form = jQuery(dom.country).closest('form');
			form.find('.cc_hide').each(function(index, item){
				jQuery(item).addClass('cc_hidden');
			});
			form.find('.cc_hide_fields_action').removeClass('cc_slider_on');
			form.data('cc_hidden',1);
			break;
		case 'manual-show':
		case 'show':
			var form = jQuery(dom.country).closest('form');
			jQuery(dom.country).trigger('change');
			form.find('.cc_hide').each(function(index, item){
				jQuery(item).removeClass('cc_hidden');
			});
			form.find('.cc_hide_fields_action').hide(200);
			form.data('cc_hidden',0);
			if(action == 'manual-show'){
				jQuery(dom.country).trigger('change');
			}
			break;
		case 'toggle':
			var form = jQuery(dom.country).closest('form');
			if(form.data('cc_hidden') == 1){
				cc_hide_fields(dom, 'show');
			} else {
				cc_hide_fields(dom, 'hide');
			}
			break;
	}
}

function cc_reveal_fields_on_error(dom){
	var form = jQuery(dom.country).closest('form');
	var errors_present = false;
	form.find('.cc_hide').each(function(index, item){
		if(jQuery(item).hasClass('_error')){
			errors_present = true;
		}
	});
	if(errors_present){
		cc_hide_fields(dom, 'show');
		form.find('.cc_hide_fields_action').hide(); // prevent the user from hiding the fields again
	}
}
requirejs(['jquery'], function( $ ) {
	jQuery( document ).ready(function() {
		if(c2a_config.enabled && c2a_config.key != null){
			var config = {
				accessToken: c2a_config.key,
				onSetCounty: function(c2a, elements, county){
					return;
				},
				domMode: 'object',
				gfxMode: c2a_config.gfx_mode,
				style: {
					ambient: c2a_config.gfx_ambient,
					accent: c2a_config.gfx_accent
				},
				showLogo: false,
				texts: c2a_config.texts,
				onResultSelected: function(c2a, elements, address){
					switch(address.country_name) {
						case 'Jersey':
							jQuery(elements.country).val('JE')
							break;
						case 'Guernsey':
							jQuery(elements.country).val('GG')
							break;
						case 'Isle of Man':
							jQuery(elements.country).val('IM')
							break;
						default:
							jQuery(elements.country).val(address.country.iso_3166_1_alpha_2);
					}
					var event = new Event('change')
					if (typeof elements.country != 'undefined') {elements.country.dispatchEvent(event)}

					var county = {
						preferred: address.province,
						code: address.province_code,
						name: address.province_name
					};

					if(elements.county.list.length == 1){
						c2a.setCounty(elements.county.list[0], county);
					}
					if(elements.county.input.length == 1){
						c2a.setCounty(elements.county.input[0], county);
					}
					
					if (typeof elements.county.input[0] != 'undefined') elements.county.input[0].dispatchEvent(event);
					if (typeof elements.county.list[0] != 'undefined') elements.county.list[0].dispatchEvent(event);
					if (typeof elements.company != 'undefined') elements.company.dispatchEvent(event);
					if (typeof elements.line_1 != 'undefined') elements.line_1.dispatchEvent(event);
					if (typeof elements.line_2 != 'undefined') elements.line_2.dispatchEvent(event);
					if (typeof elements.postcode != 'undefined') elements.postcode.dispatchEvent(event);
					if (typeof elements.town != 'undefined') elements.town.dispatchEvent(event);

					cc_hide_fields(elements,'show');
				},
				onError: function(){
					if(typeof this.activeDom.postcode !== 'undefined'){
						cc_hide_fields(this.activeDom,'show');
					} else {
						c2a_config.advanced.hide_fields = false;
					}
				},
				transliterate: c2a_config.advanced.transliterate,
				debug: c2a_config.advanced.debug,
				cssPath: false,
				tag: 'Magento 2'
			};
			if(typeof c2a_config.enabled_countries !== 'undefined'){
				config.countryMatchWith = 'iso_2';
				config.enabledCountries = c2a_config.enabled_countries;
			}
			if(c2a_config.advanced.lock_country_to_dropdown){
				config.countrySelector = false;
				config.onSearchFocus = function(c2a, dom){
					var currentCountry = dom.country.options[dom.country.selectedIndex].value;
					if(currentCountry !== ''){
						var countryCode = getCountryCode(c2a, currentCountry, 'iso_2');
						c2a.selectCountry(countryCode);
					}
				};
			}

			cc_holder = new clickToAddress(config);
			setInterval(cc_m2_c2a,200);
		}

		if(c2a_config.enabled && c2a_config.key == null){
			console.warn('ClickToAddress: Incorrect token format supplied');
		}
	});
});
