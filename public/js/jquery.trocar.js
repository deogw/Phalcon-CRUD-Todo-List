/*jslint browser:true, devel: true */
/*global jQuery */

(function ($) {

	'use strict';

    /**
     * Cros browser text selection
     * @method
     * @private
     * @param {HTMLElement} element Html element for the selection
     */
    function selectText(element) {
	    
        var range,
            selection;
        
	    if (document.body.createTextRange) {
	        range = document.body.createTextRange();
	        range.moveToElementText(element);
	        range.select();

	    } else if (window.getSelection) {

	        selection = window.getSelection();
	        range = document.createRange();
	        range.selectNodeContents(element);
	        selection.removeAllRanges();
	        selection.addRange(range);
	    }
	}
    
    /**
     * Public methods for the plugin
     * @property {Object}
     */
    var methods = {
        
        /**
         * Enable/Disable editing mode
         * @method
         * @public
         * @param {Boolean} active Enable if true, disable is false
         */
		editable: function (active) {

			var $this = $(this),
                params = $this.data('trocar-params'),
                isDirty;
            
			if (active === true) {
				$this.attr('contentEditable', true);
				$this.addClass('trocar-editable');

				$this.trigger('start-edit.trocar');

				if (params.selectOnEdit) {
					selectText(this[0]);
				}

			} else {

				if (params.on !== 'always') {
					$this.attr('contentEditable', false);
					$this.removeClass('trocar-editable');
				}

				isDirty = ($.trim($this.html()) !== $.trim($this.data('trocar-original')));
	
				$this.trigger('end-edit.trocar', [isDirty, $this.data('trocar-original')]);
			}

			$this.data('trocar-original', $this.html());
		},

        /**
         * Destroy the plugin instance, remove event listener, etc
         * @method
         * @public
         */
		destroy: function () {

			this.each(function () {
				var $this = $(this),
                    params = $this.data('trocar-params'),
                    placeholder = $this.attr('data-placeholder');

				if (params) {
					if (params.on && params.on.constructor === String && params.on !== 'always') {
						$this.off(params.on);
					}

					if (params.off && params.off.constructor === String) {
						$this.off(params.off);
					} else {
						$this.off('blur', function () {
							$this.trocar('editable', false);
						});
					}

					if (params.endOnEnter) {
						$this.off('keydown', function (event) {

							if (event.which === 13) {
								$this.trocar('editable', false);
								$this.blur();
								return false;
							}
						});
					}
                    
                    if (placeholder) {
                        if ($this.html().trim() === placeholder) {
                            $this.html('');
                        }
                    }
				}

				$this.attr('contentEditable', false);
				$this.removeClass('trocar-editable');
				$this.removeData('trocar-params');
				$this.removeData('trocar-original');
			});
		}
	};
    
    /**
     * Initialize method for the plugin
     * @method
     * @param {Object} params Configuration for the plugin
     */
	function init($elements, params) {

        // Initalize every item
		$elements.each(function () {

			var $this = $(this),
                placeholder = $this.attr('data-placeholder');
            
			$this.data('trocar-params', params);

			if (params.on && params.on.constructor === String) {

				if (params.on === 'always') {
					$this.trocar('editable', true);
				} else {
					$this.on(params.on, function () {
						$this.trocar('editable', true);
					});
				}

				if (params.off && params.off.constructor === String) {
					$this.on(params.off, function () {
						$this.trocar('editable', false);
					});
				} else {
					$this.on('blur', function () {
						$this.trocar('editable', false);
					});
				}
			}

			if (params.endOnEnter) {
				$this.on('keydown', function (event) {

					if (event.which === 13) {
						$this.trocar('editable', false);
						$this.blur();
						return false;
					}
				});
			}

			if (placeholder) {
                $this.on('focus', function () {
                    if ($this.html().trim() === placeholder) {
                        $this.html('');
                        $this.removeClass('has-placeholder');
                    }
                });
                
                $this.on('blur', function () {
                    if (!$this.html().trim()) {
                        $this.html(placeholder);
                        $this.addClass('has-placeholder');
                    }
                }).trigger('blur');
            }
            
            if (params.filterPaste) {
                $this.on('paste', function (e) {
                    var $this = $(e.currentTarget);
                    window.setTimeout(function () {
                        $this.html(e.currentTarget.innerText);
                    }, 0);
                });
            }
		});
	}

    /**
     * jQuery interface
     * @method
     * @public
     * @param {Object} method Configuration options or the method to be invoked
     */
    $.fn.trocar = function (method) {

		if (methods[method]) {
			return methods[method].apply($(this), Array.prototype.slice.call(arguments, 1));
		} else if (method && method.constructor === Object) {
			return init(this, method);
		}
	};

}(jQuery));