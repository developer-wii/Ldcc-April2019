jQuery(function(i){i(".woocommerce-ordering").on("change","select.orderby",function(){i(this).closest("form").submit()}),i("input.qty:not(.product-quantity input.qty)").each(function(){var o=parseFloat(i(this).attr("min"));0<=o&&parseFloat(i(this).val())<o&&i(this).val(o)}),i(".woocommerce-store-notice__dismiss-link").click(function(){Cookies.set("store_notice","hidden",{path:"/"}),i(".woocommerce-store-notice").hide()}),"hidden"===Cookies.get("store_notice")?i(".woocommerce-store-notice").hide():i(".woocommerce-store-notice").show(),i(document.body).on("click",function(){i(".woocommerce-input-wrapper span.description:visible").prop("aria-hidden",!0).slideUp(250)}),i(".woocommerce-input-wrapper").on("click",function(o){o.stopPropagation()}),i(".woocommerce-input-wrapper :input").on("keydown",function(o){var e=i(this).parent().find("span.description");if(27===o.which&&e.length&&e.is(":visible"))return e.prop("aria-hidden",!0).slideUp(250),o.preventDefault(),!1}).on("click focus",function(){var o=i(this).parent(),e=o.find("span.description");o.addClass("currentTarget"),i(".woocommerce-input-wrapper:not(.currentTarget) span.description:visible").prop("aria-hidden",!0).slideUp(250),e.length&&e.is(":hidden")&&e.prop("aria-hidden",!1).slideDown(250),o.removeClass("currentTarget")}),i.scroll_to_notices=function(o){o.length&&i("html, body").stop()}});