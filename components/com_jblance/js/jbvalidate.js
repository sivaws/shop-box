var JFormValidator = new Class(
		{
			initialize : function() {
				this.handlers = Object();
				this.custom = Object();
				this.setHandler("username", function(b) {
					regex = new RegExp("[<|>|\"|'|%|;|(|)|&]", "i");
					return !regex.test(b)
				});
				this.setHandler("password", function(b) {
					regex = /^\S[\S ]{2,98}\S$/;
					return regex.test(b)
				});
				this.setHandler('passverify',
						function (value) {
						return ($('password').value == value);
				}
				); // added March 2011
				this.setHandler("numeric", function(b) {
					regex = /^(\d|-)?(\d|,)*\.?\d*$/;
					return regex.test(b)
				});
				this.setHandler(
								"email",
								function(b) {
									regex = /^[a-zA-Z0-9._-]+(\+[a-zA-Z0-9._-]+)*@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
									return regex.test(b)
								});
				var a = $$("form.form-validate");
				a.each(function(b) {
					this.attachToForm(b)
				}, this)
			},
			setHandler : function(b, c, a) {
				a = (a == "") ? true : a;
				this.handlers[b] = {
					enabled : a,
					exec : c
				}
			},
			attachToForm : function(a) {
				a.getElements("input,textarea,select")
						.each(
								function(b) {
									if (($(b).get("tag") == "input" || $(b)
											.get("tag") == "button")
											&& $(b).get("type") == "submit") {
										if (b.hasClass("validate")) {
											b.onclick = function() {
												return document.formvalidator
														.isValid(this.form)
											}
										}
									} else {
										b.addEvent("blur", function() {
											return document.formvalidator
													.validate(this)
										})
									}
								})
			},
			validate : function(c) {
				c = $(c);
				if (c.get("disabled")) {
					this.handleResponse(true, c);
					return true
				}
				if (c.hasClass("required")) {
					if (c.get("tag") == "fieldset"
							&& (c.hasClass("radio") || c.hasClass("checkboxes"))) {
						for ( var a = 0;; a++) {
							if (document.id(c.get("id") + a)) {
								if (document.id(c.get("id") + a).checked) {
									break
								}
							} else {
								this.handleResponse(false, c);
								return false
							}
						}
					} else {
						if (!(c.get("value"))) {
							this.handleResponse(false, c);
							return false
						}
					}
				}
				var b = (c.className && c.className
						.search(/validate-([a-zA-Z0-9\_\-]+)/) != -1) ? c.className
						.match(/validate-([a-zA-Z0-9\_\-]+)/)[1]
						: "";
				if (b == "") {
					this.handleResponse(true, c);
					return true
				}
				if ((b) && (b != "none") && (this.handlers[b])
						&& c.get("value")) {
					if (this.handlers[b].exec(c.get("value")) != true) {
						this.handleResponse(false, c);
						return false
					}
				}
				this.handleResponse(true, c);
				return true
			},
			isValid : function(c) {
				var b = true;
				var d = c.getElements("fieldset").concat($A(c.elements));
				for ( var a = 0; a < d.length; a++) {
					if (this.validate(d[a]) == false) {
						b = false
					}
				}
				new Hash(this.custom).each(function(e) {
					if (e.exec() != true) {
						b = false
					}
				});
				return b
			},
			handleResponse : function(b, a) {
				if (!(a.labelref)) {
					var c = $$("label");
					c.each(function(d) {
						if (d.get("for") == a.get("id")) {
							a.labelref = d
						}
					})
				}
				if (b == false) {
					a.addClass("invalid");
					a.set("aria-invalid", "true");
					if (a.labelref) {
						document.id(a.labelref).addClass("invalid");
						document.id(a.labelref).set("aria-invalid", "true");
					}
				} else {
					a.removeClass("invalid");
					a.set("aria-invalid", "false");
					if (a.labelref) {
						document.id(a.labelref).removeClass("invalid");
						document.id(a.labelref).set("aria-invalid", "false");
					}
				}
			}
		});
document.formvalidator = null;
window.addEvent("domready", function() {
	document.formvalidator = new JFormValidator()
});