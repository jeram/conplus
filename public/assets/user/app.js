/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 105);
/******/ })
/************************************************************************/
/******/ ({

/***/ 105:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(106);


/***/ }),

/***/ 106:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

(function ($) {
  $.fn.jsonTagEditor = function (options, val, blur) {
    function escape(tag) {
      return tag.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;");
    }function validateTagArray(json) {
      try {
        return JSON.parse(json).map(function (tagArrayElement) {
          return validate(JSON.stringify(tagArrayElement));
        }).filter(function (element) {
          return element;
        });
      } catch (e) {
        return json.split(o.dregex).map(function (tagArrayElement) {
          return tagArrayElement.trim().length > 0 ? { value: tagArrayElement.trim() } : false;
        }).filter(function (element) {
          return element;
        });
      }
    }function validateParsedTag(tagObject) {
      return tagObject.hasOwnProperty("value") && typeof tagObject.value === "string" && tagObject.value.trim().length > 0;
    }function validate(tag) {
      try {
        var parsedTag = JSON.parse(tag);if (validateParsedTag(parsedTag)) {
          return parsedTag;
        }
      } catch (e) {}return String(tag).trim().length > 0 ? { value: String(tag).trim() } : false;
    }function ellipsify(str, maxLength) {
      return maxLength > -1 && str.length > maxLength ? str.substring(0, maxLength - 1) + "…" : str;
    }function deepEquals(arr1, arr2) {
      return $(arr1).not(arr2).length === 0 && $(arr2).not(arr1).length === 0;
    }var blurResult,
        o = $.extend({}, $.fn.jsonTagEditor.defaults, options),
        selector = this;o.delimiter = "\t\n";o.dregex = new RegExp("[" + o.delimiter + "]", "g");if (typeof options === "string") {
      var response = [];selector.each(function () {
        var el = $(this),
            o = el.data("options"),
            ed = el.next(".json-tag-editor");switch (options) {case "getTags":
            response.push({ field: el[0], editor: ed, tags: ed.data("tags") });break;case "addTag":
            if (o.maxTags && ed.data("tags").length >= o.maxTags) {
              return false;
            }$("<li></li>").append('<div class="json-tag-editor-spacer">&nbsp;' + o.delimiter[0] + "</div>").append('<div class="json-tag-editor-tag"></div>').append('<div class="json-tag-editor-delete"><i></i></div>').appendTo(ed).find(".json-tag-editor-tag").append('<input type="text" maxlength="' + o.maxLength + '">').addClass("active").find("input").val(val).blur();if (!blur) {
              ed.click();
            } else {
              $(".placeholder", ed).remove();
            }break;case "removeTag":
            $(".json-tag-editor-tag", ed).filter(function () {
              return $(this).get(0).dataset.value === val;
            }).closest("li").find(".json-tag-editor-delete").click();if (!blur) {
              ed.click();
            }break;case "destroy":
            el.removeClass("json-tag-editor-hidden-src").removeData("options").off("focus.json-tag-editor").next(".json-tag-editor").remove();break;default:
            return this;}
      });return options === "getTags" ? response : this;
    }if (window.getSelection) {
      $(document).off("keydown.json-tag-editor").on("keydown.json-tag-editor", function (e) {
        if (e.which === 8 || e.which === 46) {
          try {
            var sel = getSelection(),
                el = document.activeElement.tagName === "BODY" ? $(sel.getRangeAt(0).startContainer.parentNode).closest(".json-tag-editor") : 0;
          } catch (e) {
            el = 0;
          }if (sel.rangeCount > 0 && el && el.length) {
            $(".json-tag-editor-tag", el).each(function () {
              if (sel.containsNode($(this).get(0))) {
                $(this).closest("li").find(".json-tag-editor-delete").click();
              }
            });return false;
          }
        }
      });
    }return selector.each(function () {
      var el = $(this),
          tagList = [];var ed = $("<ul " + (o.clickDelete ? 'oncontextmenu="return false;" ' : "") + 'class="json-tag-editor' + (options.noSelect ? ' noselect"' : '"') + "></ul>").insertAfter(el);el.addClass("json-tag-editor-hidden-src").data("options", o).on("focus.json-tag-editor", function () {
        ed.click();
      });ed.append('<li style="width:1px">&nbsp;</li>');var newTag = "<li>" + '<div class="json-tag-editor-spacer">&nbsp;' + o.delimiter[0] + "</div>" + '<div class="json-tag-editor-tag"></div>' + '<div class="json-tag-editor-delete"><i></i></div>' + "</li>";function setPlaceholder() {
        if (o.placeholder && !tagList.length && !$(".deleted, .placeholder, input", ed).length) {
          ed.append('<li class="placeholder"><div>' + o.placeholder + "</div></li>");
        }
      }function updateGlobals(init) {
        var oldTags = tagList;tagList = $(".json-tag-editor-tag:not(.deleted)", ed).map(function (i, e) {
          var tag = {};if ($(this).hasClass("active")) {
            Object.assign(tag, $(this).find("input").get(0).dataset);tag.value = $(this).find("input").val();
          } else {
            Object.assign(tag, $(e).get(0).dataset, { value: $(e).get(0).dataset.value });
          }if (tag.value) {
            return tag;
          }
        }).get();ed.data("tags", tagList);el.val(tagList.reduce(function (previous, current) {
          return previous + o.delimiter[0] + current.value;
        }, ""));if (!init) {
          if (!deepEquals(oldTags, tagList)) {
            o.onChange(el, ed, tagList);
          }
        }setPlaceholder();
      }ed.click(function (e, closestTag) {
        var d,
            dist = 99999,
            loc;if (window.getSelection && getSelection().toString() !== "") {
          return;
        }if (o.maxTags && ed.data("tags").length >= o.maxTags) {
          ed.find("input").blur();return false;
        }blurResult = true;$("input:focus", ed).blur();if (!blurResult) {
          return false;
        }blurResult = true;$(".placeholder", ed).remove();if (closestTag && closestTag.length) {
          loc = "before";
        } else {
          $(".json-tag-editor-tag", ed).each(function () {
            var tag = $(this),
                to = tag.offset(),
                tagX = to.left,
                tagY = to.top;if (e.pageY >= tagY && e.pageY <= tagY + tag.height()) {
              if (e.pageX < tagX) {
                loc = "before";d = tagX - e.pageX;
              } else {
                loc = "after";d = e.pageX - tagX - tag.width();
              }if (d < dist) dist = d;closestTag = tag;
            }
          });
        }if (loc === "before") {
          $(newTag).insertBefore(closestTag.closest("li")).find(".json-tag-editor-tag").click();
        } else if (loc === "after") {
          $(newTag).insertAfter(closestTag.closest("li")).find(".json-tag-editor-tag").click();
        } else {
          $(newTag).appendTo(ed).find(".json-tag-editor-tag").click();
        }return false;
      });ed.on("click", ".json-tag-editor-delete", function (e) {
        if ($(this).prev().hasClass("active")) {
          $(this).closest("li").find("input").caret(-1);return false;
        }var li = $(this).closest("li"),
            tag = li.find(".json-tag-editor-tag");if (o.beforeTagDelete(el, ed, tagList, tag.text()) === false) {
          return false;
        }tag.addClass("deleted").animate({ width: 0 }, o.animateDelete, function () {
          li.remove();setPlaceholder();
        });updateGlobals();return false;
      });if (o.clickDelete) {
        ed.on("mousedown", ".json-tag-editor-tag", function (e) {
          if (e.ctrlKey || e.which > 1) {
            var li = $(this).closest("li"),
                tag = li.find(".json-tag-editor-tag");if (o.beforeTagDelete(el, ed, tagList, tag.text()) === false) {
              return false;
            }tag.addClass("deleted").animate({ width: 0 }, o.animateDelete, function () {
              li.remove();setPlaceholder();
            });updateGlobals();return false;
          }
        });
      }ed.on("click", ".json-tag-editor-tag", function (e) {
        if (o.clickDelete && (e.ctrlKey || e.which > 1)) {
          return false;
        }if (!$(this).hasClass("active")) {
          var value = $(this).get(0).dataset.value ? $(this).get(0).dataset.value : $(this).text(),
              tagDisplay = $(this).text();var leftPercent = Math.abs(($(this).offset().left - e.pageX) / $(this).width()),
              caretPos = parseInt(tagDisplay.length * leftPercent),
              input = $(this).html('<input type="text" maxlength="' + o.maxLength + '" value="' + escape(value) + '">').addClass("active").find("input");input.data("old_tag", value).focus().caret(caretPos);if (o.autocomplete) {
            var aco = $.extend({}, o.autocomplete);var acSelect = "select" in aco ? o.autocomplete.select : "";aco.select = function (e, ui) {
              if (acSelect) {
                acSelect(e, ui);
              }setTimeout(function () {
                ed.trigger("click", [$(".active", ed).find("input").closest("li").next("li").find(".json-tag-editor-tag")]);
              }, 20);
            };if (aco.plugin) {
              input[aco.plugin](aco);
            } else {
              input.autocomplete(aco);
            }if (aco._renderItem) {
              input.autocomplete("instance")._renderItem = aco._renderItem;
            }
          }
        }return false;
      });function splitCleanup(input, text) {
        var li = input.closest("li"),
            subTags = text ? text.replace(/ +/, " ").split(o.dregex) : input.val().replace(/ +/, " ").split(o.dregex),
            oldTag = input.data("old_tag"),
            oldTags = tagList.slice(0),
            exceeded = false,
            cbVal;for (var i = 0; i < subTags.length; i++) {
          var tag = $.trim(subTags[i]).slice(0, o.maxLength);if (o.forceLowercase) {
            tag = tag.toLowerCase();
          }cbVal = o.beforeTagSave(el, ed, oldTags, oldTag, tag);tag = cbVal || tag;if (cbVal === false || !tag) {
            continue;
          }var tagObject = validate(tag);if (tagObject) {
            var $tagEditorTag = $('<div class="json-tag-editor-tag"' + (tag.length > o.maxTagLength ? ' title="' + escape(tagObject.value) + '"' : "") + ">" + escape(ellipsify(tagObject.value, o.maxTagLength)) + "</div>");var tagProperties = Object.keys(tagObject);for (var j = 0; j < tagProperties.length; j++) {
              $tagEditorTag.get(0).dataset[tagProperties[j]] = tagObject[tagProperties[j]];
            }oldTags.push(tagObject);li.before($("<li></li>").append('<div class="json-tag-editor-spacer">&nbsp;' + o.delimiter[0] + "</div>").append($tagEditorTag).append('<div class="json-tag-editor-delete"><i></i></div>'));if (o.maxTags && oldTags.length >= o.maxTags) {
              exceeded = true;break;
            }
          }
        }input.closest("li").remove();updateGlobals();
      }ed.on("blur", "input", function (e) {
        e.stopPropagation();var input = $(this),
            oldTag = input.data("old_tag"),
            tag = $.trim(input.val().replace(/ +/, " ").replace(o.dregex, o.delimiter[0]));if (!tag) {
          if (oldTag && o.beforeTagDelete(el, ed, tagList, oldTag) === false) {
            input.val(oldTag).focus();blurResult = false;updateGlobals();return;
          }try {
            input.closest("li").remove();
          } catch (e) {}if (oldTag) {
            updateGlobals();
          }
        } else if (tag.indexOf(o.delimiter[0]) >= 0) {
          splitCleanup(input);return;
        } else if (tag != oldTag) {
          if (o.forceLowercase) {
            tag = tag.toLowerCase();
          }var cbVal = o.beforeTagSave(el, ed, tagList, oldTag, tag);tag = cbVal || tag;if (cbVal === false) {
            if (oldTag) {
              input.val(oldTag).focus();blurResult = false;updateGlobals();return;
            }try {
              input.closest("li").remove();
            } catch (e) {}if (oldTag) {
              updateGlobals();
            }
          }
        }var $tagEditorTag = input.parent(),
            tagObject = validate(tag);if (tagObject) {
          var tagProperties = Object.keys(tagObject);for (var i = 0; i < tagProperties.length; i++) {
            $tagEditorTag.get(0).dataset[tagProperties[i]] = tagObject[tagProperties[i]];
          }if (tagObject.value.length > o.maxTagLength) {
            $tagEditorTag.attr("title", escape(tagObject.value));
          } else {
            $tagEditorTag.removeAttr("title");
          }$tagEditorTag.html(escape(ellipsify(tagObject.value, o.maxTagLength))).removeClass("active");
        }if (tag != oldTag) {
          updateGlobals();
        }setPlaceholder();
      });ed.on("paste", "input", function (e) {
        var pastedContent, inputContent;$(this).removeAttr("maxlength");pastedContent = (e.originalEvent || e).clipboardData.getData("text/plain");inputContent = $(this);setTimeout(function () {
          splitCleanup(inputContent, pastedContent);
        }, 30);
      });ed.on("keypress", "input", function (e) {
        if (o.delimiter.indexOf(String.fromCharCode(e.which)) >= 0) {
          var inp = $(this);setTimeout(function () {
            splitCleanup(inp);
          }, 20);
        }
      });ed.on("keydown", "input", function (e) {
        var $this = $(this),
            previousTag,
            nextTag;if ((e.which === 37 || !o.autocomplete && e.which === 38) && !$this.caret() || e.which === 8 && !$this.val()) {
          previousTag = $this.closest("li").prev("li").find(".json-tag-editor-tag");if (previousTag.length) {
            previousTag.click().find("input").caret(-1);
          } else if ($this.val() && !(o.maxTags && ed.data("tags").length >= o.maxTags)) {
            $(newTag).insertBefore($this.closest("li")).find(".json-tag-editor-tag").click();
          }return false;
        } else if ((e.which === 39 || !o.autocomplete && e.which === 40) && $this.caret() === $this.val().length) {
          nextTag = $this.closest("li").next("li").find(".json-tag-editor-tag");if (nextTag.length) {
            nextTag.click().find("input").caret(0);
          } else if ($this.val()) {
            ed.click();
          }return false;
        } else if (e.which === 9) {
          if (e.shiftKey) {
            previousTag = $this.closest("li").prev("li").find(".json-tag-editor-tag");if (previousTag.length) {
              previousTag.click().find("input").caret(0);
            } else if ($this.val() && !(o.maxTags && ed.data("tags").length >= o.maxTags)) {
              $(newTag).insertBefore($this.closest("li")).find(".json-tag-editor-tag").click();
            } else {
              el.attr("disabled", "disabled");setTimeout(function () {
                el.removeAttr("disabled");
              }, 30);return;
            }return false;
          } else {
            nextTag = $this.closest("li").next("li").find(".json-tag-editor-tag");if (nextTag.length) {
              nextTag.click().find("input").caret(0);
            } else if ($this.val()) {
              ed.click();
            } else {
              return;
            }return false;
          }
        } else if (e.which === 46 && (!$.trim($this.val()) || $this.caret() === $this.val().length)) {
          nextTag = $this.closest("li").next("li").find(".json-tag-editor-tag");if (nextTag.length) {
            nextTag.click().find("input").caret(0);
          } else if ($this.val()) {
            ed.click();
          }return false;
        } else if (e.which === 13) {
          ed.trigger("click", [$this.closest("li").next("li").find(".json-tag-editor-tag")]);if (o.maxTags && ed.data("tags").length >= o.maxTags) {
            ed.find("input").blur();
          }return false;
        } else if (e.which === 36 && !$this.caret()) {
          ed.find(".json-tag-editor-tag").first().click();
        } else if (e.which === 35 && $this.caret() === $this.val().length) {
          ed.find(".json-tag-editor-tag").last().click();
        } else if (e.which === 27) {
          $this.val($this.data("old_tag") ? $this.data("old_tag") : "").blur();return false;
        }
      });var tags = o.initialTags.length ? o.initialTags.map(function (element) {
        return validateParsedTag(element) ? element : validate(element);
      }) : validateTagArray(el.val());for (var i = 0; i < tags.length; i++) {
        if (o.maxTags && i >= o.maxTags) {
          break;
        }var tagObject = tags[i];if (tagObject) {
          if (o.forceLowercase) {
            tagObject.value = tagObject.value.toLowerCase();
          }tagList.push(tagObject);var $tagEditorTag = $('<div class="json-tag-editor-tag"' + (tagObject.length > o.maxTagLength ? ' title="' + escape(tagObject.value) + '"' : "") + ">" + escape(ellipsify(tagObject.value, o.maxTagLength)) + "</div>");var tagProperties = Object.keys(tagObject);for (var j = 0; j < tagProperties.length; j++) {
            $tagEditorTag.get(0).dataset[tagProperties[j]] = tagObject[tagProperties[j]];
          }ed.append($("<li></li>").append('<div class="json-tag-editor-spacer">&nbsp;' + o.delimiter[0] + "</div>").append($tagEditorTag).append('<div class="json-tag-editor-delete"><i></i></div>'));
        }
      }updateGlobals(true);if (o.sortable && $.fn.sortable) {
        ed.sortable({ distance: 5, cancel: ".json-tag-editor-spacer, input", helper: "clone", update: function update() {
            updateGlobals();
          } });
      }
    });
  };$.fn.jsonTagEditor.defaults = { initialTags: [], maxTags: 0, maxLength: 50, maxTagLength: -1, placeholder: "", forceLowercase: false, clickDelete: false, animateDelete: 175, noSelect: false, sortable: true, autocomplete: null, onChange: function onChange() {}, beforeTagSave: function beforeTagSave() {}, beforeTagDelete: function beforeTagDelete() {} };
})(jQuery);

/***/ })

/******/ });