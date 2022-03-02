!function (t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define(e) : t.Sweetalert2 = e()
}(this, function () {
    "use strict";

    function V(t) {
        return (V = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        })(t)
    }

    function a(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    function r(t, e) {
        for (var n = 0; n < e.length; n++) {
            var o = e[n];
            o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
        }
    }

    function s() {
        return (s = Object.assign || function (t) {
            for (var e = 1; e < arguments.length; e++) {
                var n = arguments[e];
                for (var o in n) Object.prototype.hasOwnProperty.call(n, o) && (t[o] = n[o])
            }
            return t
        }).apply(this, arguments)
    }

    function c(t) {
        return (c = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
            return t.__proto__ || Object.getPrototypeOf(t)
        })(t)
    }

    function u(t, e) {
        return (u = Object.setPrototypeOf || function (t, e) {
            return t.__proto__ = e, t
        })(t, e)
    }

    function o(t, e, n) {
        return (o = function () {
            if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
            if (Reflect.construct.sham) return !1;
            if ("function" == typeof Proxy) return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], function () {
                })), !0
            } catch (t) {
                return !1
            }
        }() ? Reflect.construct : function (t, e, n) {
            var o = [null];
            o.push.apply(o, e);
            var i = new (Function.bind.apply(t, o));
            return n && u(i, n.prototype), i
        }).apply(null, arguments)
    }

    function l(t, e) {
        return !e || "object" != typeof e && "function" != typeof e ? function (t) {
            if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return t
        }(t) : e
    }

    function d(t, e, n) {
        return (d = "undefined" != typeof Reflect && Reflect.get ? Reflect.get : function (t, e, n) {
            var o = function (t, e) {
                for (; !Object.prototype.hasOwnProperty.call(t, e) && null !== (t = c(t));) ;
                return t
            }(t, e);
            if (o) {
                var i = Object.getOwnPropertyDescriptor(o, e);
                return i.get ? i.get.call(n) : i.value
            }
        })(t, e, n || t)
    }

    var e = "SweetAlert2:", p = function (t) {
            return Array.prototype.slice.call(t)
        }, q = function (t) {
            console.warn("".concat(e, " ").concat(t))
        }, H = function (t) {
            console.error("".concat(e, " ").concat(t))
        }, i = [], j = function (t) {
            return "function" == typeof t ? t() : t
        }, R = function (t) {
            return t && Promise.resolve(t) === t
        }, t = Object.freeze({cancel: "cancel", backdrop: "backdrop", close: "close", esc: "esc", timer: "timer"}),
        n = function (t) {
            var e = {};
            for (var n in t) e[t[n]] = "swal2-" + t[n];
            return e
        },
        I = n(["container", "shown", "height-auto", "iosfix", "popup", "modal", "no-backdrop", "toast", "toast-shown", "toast-column", "fade", "show", "hide", "noanimation", "close", "title", "header", "content", "actions", "confirm", "cancel", "footer", "icon", "image", "input", "file", "range", "select", "radio", "checkbox", "label", "textarea", "inputerror", "validation-message", "progress-steps", "active-progress-step", "progress-step", "progress-step-line", "loading", "styled", "top", "top-start", "top-end", "top-left", "top-right", "center", "center-start", "center-end", "center-left", "center-right", "bottom", "bottom-start", "bottom-end", "bottom-left", "bottom-right", "grow-row", "grow-column", "grow-fullscreen", "rtl"]),
        f = n(["success", "warning", "info", "question", "error"]), m = {previousBodyPadding: null},
        g = function (t, e) {
            return t.classList.contains(e)
        }, N = function (t) {
            if (t.focus(), "file" !== t.type) {
                var e = t.value;
                t.value = "", t.value = e
            }
        }, h = function (t, e, n) {
            t && e && ("string" == typeof e && (e = e.split(/\s+/).filter(Boolean)), e.forEach(function (e) {
                t.forEach ? t.forEach(function (t) {
                    n ? t.classList.add(e) : t.classList.remove(e)
                }) : n ? t.classList.add(e) : t.classList.remove(e)
            }))
        }, D = function (t, e) {
            h(t, e, !0)
        }, U = function (t, e) {
            h(t, e, !1)
        }, _ = function (t, e) {
            for (var n = 0; n < t.childNodes.length; n++) if (g(t.childNodes[n], e)) return t.childNodes[n]
        }, z = function (t) {
            t.style.opacity = "", t.style.display = t.id === I.content ? "block" : "flex"
        }, W = function (t) {
            t.style.opacity = "", t.style.display = "none"
        }, K = function (t) {
            return !(!t || !(t.offsetWidth || t.offsetHeight || t.getClientRects().length))
        }, v = function () {
            return document.body.querySelector("." + I.container)
        }, b = function (t) {
            var e = v();
            return e ? e.querySelector(t) : null
        }, y = function (t) {
            return b("." + t)
        }, w = function () {
            return y(I.popup)
        }, C = function () {
            var t = w();
            return p(t.querySelectorAll("." + I.icon))
        }, k = function () {
            return y(I.title)
        }, B = function () {
            return y(I.content)
        }, x = function () {
            return y(I.image)
        }, A = function () {
            return y(I["progress-steps"])
        }, P = function () {
            return y(I["validation-message"])
        }, S = function () {
            return b("." + I.actions + " ." + I.confirm)
        }, L = function () {
            return b("." + I.actions + " ." + I.cancel)
        }, F = function () {
            return y(I.actions)
        }, Z = function () {
            return y(I.header)
        }, Q = function () {
            return y(I.footer)
        }, Y = function () {
            return y(I.close)
        }, $ = function () {
            var t = p(w().querySelectorAll('[tabindex]:not([tabindex="-1"]):not([tabindex="0"])')).sort(function (t, e) {
                    return t = parseInt(t.getAttribute("tabindex")), (e = parseInt(e.getAttribute("tabindex"))) < t ? 1 : t < e ? -1 : 0
                }),
                e = p(w().querySelectorAll('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable], audio[controls], video[controls]')).filter(function (t) {
                    return "-1" !== t.getAttribute("tabindex")
                });
            return function (t) {
                for (var e = [], n = 0; n < t.length; n++) -1 === e.indexOf(t[n]) && e.push(t[n]);
                return e
            }(t.concat(e)).filter(function (t) {
                return K(t)
            })
        }, E = function () {
            return !T() && !document.body.classList.contains(I["no-backdrop"])
        }, T = function () {
            return document.body.classList.contains(I["toast-shown"])
        }, O = function () {
            return "undefined" == typeof window || "undefined" == typeof document
        },
        M = '\n <div aria-labelledby="'.concat(I.title, '" aria-describedby="').concat(I.content, '" class="').concat(I.popup, '" tabindex="-1">\n   <div class="').concat(I.header, '">\n     <ul class="').concat(I["progress-steps"], '"></ul>\n     <div class="').concat(I.icon, " ").concat(f.error, '">\n       <span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span>\n     </div>\n     <div class="').concat(I.icon, " ").concat(f.question, '"></div>\n     <div class="').concat(I.icon, " ").concat(f.warning, '"></div>\n     <div class="').concat(I.icon, " ").concat(f.info, '"></div>\n     <div class="').concat(I.icon, " ").concat(f.success, '">\n       <div class="swal2-success-circular-line-left"></div>\n       <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>\n       <div class="swal2-success-ring"></div> <div class="swal2-success-fix"></div>\n       <div class="swal2-success-circular-line-right"></div>\n     </div>\n     <img class="').concat(I.image, '" />\n     <h2 class="').concat(I.title, '" id="').concat(I.title, '"></h2>\n     <button type="button" class="').concat(I.close, '">&times;</button>\n   </div>\n   <div class="').concat(I.content, '">\n     <div id="').concat(I.content, '"></div>\n     <input class="').concat(I.input, '" />\n     <input type="file" class="').concat(I.file, '" />\n     <div class="').concat(I.range, '">\n       <input type="range" />\n       <output></output>\n     </div>\n     <select class="').concat(I.select, '"></select>\n     <div class="').concat(I.radio, '"></div>\n     <label for="').concat(I.checkbox, '" class="').concat(I.checkbox, '">\n       <input type="checkbox" />\n       <span class="').concat(I.label, '"></span>\n     </label>\n     <textarea class="').concat(I.textarea, '"></textarea>\n     <div class="').concat(I["validation-message"], '" id="').concat(I["validation-message"], '"></div>\n   </div>\n   <div class="').concat(I.actions, '">\n     <button type="button" class="').concat(I.confirm, '">OK</button>\n     <button type="button" class="').concat(I.cancel, '">Cancel</button>\n   </div>\n   <div class="').concat(I.footer, '">\n   </div>\n </div>\n').replace(/(^|\n)\s*/g, ""),
        J = function (t) {
            var e = v();
            if (e && (e.parentNode.removeChild(e), U([document.documentElement, document.body], [I["no-backdrop"], I["toast-shown"], I["has-column"]])), !O()) {
                var n = document.createElement("div");
                n.className = I.container, n.innerHTML = M;
                var o = "string" == typeof t.target ? document.querySelector(t.target) : t.target;
                o.appendChild(n);
                var i, r = w(), a = B(), s = _(a, I.input), c = _(a, I.file),
                    u = a.querySelector(".".concat(I.range, " input")),
                    l = a.querySelector(".".concat(I.range, " output")), d = _(a, I.select),
                    p = a.querySelector(".".concat(I.checkbox, " input")), f = _(a, I.textarea);
                r.setAttribute("role", t.toast ? "alert" : "dialog"), r.setAttribute("aria-live", t.toast ? "polite" : "assertive"), t.toast || r.setAttribute("aria-modal", "true"), "rtl" === window.getComputedStyle(o).direction && D(v(), I.rtl);
                var m = function (t) {
                    Et.isVisible() && i !== t.target.value && Et.resetValidationMessage(), i = t.target.value
                };
                return s.oninput = m, c.onchange = m, d.onchange = m, p.onchange = m, f.oninput = m, u.oninput = function (t) {
                    m(t), l.value = u.value
                }, u.onchange = function (t) {
                    m(t), u.nextSibling.value = u.value
                }, r
            }
            H("SweetAlert2 requires document to initialize")
        }, X = function (t, e) {
            if (!t) return W(e);
            if (t instanceof HTMLElement) e.appendChild(t); else if ("object" === V(t)) if (e.innerHTML = "", 0 in t) for (var n = 0; n in t; n++) e.appendChild(t[n].cloneNode(!0)); else e.appendChild(t.cloneNode(!0)); else t && (e.innerHTML = t);
            z(e)
        }, G = function () {
            if (O()) return !1;
            var t = document.createElement("div"), e = {
                WebkitAnimation: "webkitAnimationEnd",
                OAnimation: "oAnimationEnd oanimationend",
                animation: "animationend"
            };
            for (var n in e) if (e.hasOwnProperty(n) && void 0 !== t.style[n]) return e[n];
            return !1
        }(), tt = function (t) {
            var e = F(), n = S(), o = L();
            if (t.showConfirmButton || t.showCancelButton ? z(e) : W(e), t.showCancelButton ? o.style.display = "inline-block" : W(o), t.showConfirmButton ? n.style.removeProperty("display") : W(n), n.innerHTML = t.confirmButtonText, o.innerHTML = t.cancelButtonText, n.setAttribute("aria-label", t.confirmButtonAriaLabel), o.setAttribute("aria-label", t.cancelButtonAriaLabel), n.className = I.confirm, D(n, t.confirmButtonClass), t.customClass && D(n, t.customClass.confirmButton), o.className = I.cancel, D(o, t.cancelButtonClass), t.customClass && D(o, t.customClass.cancelButton), t.buttonsStyling) {
                D([n, o], I.styled), t.confirmButtonColor && (n.style.backgroundColor = t.confirmButtonColor), t.cancelButtonColor && (o.style.backgroundColor = t.cancelButtonColor);
                var i = window.getComputedStyle(n).getPropertyValue("background-color");
                n.style.borderLeftColor = i, n.style.borderRightColor = i
            } else U([n, o], I.styled), n.style.backgroundColor = n.style.borderLeftColor = n.style.borderRightColor = "", o.style.backgroundColor = o.style.borderLeftColor = o.style.borderRightColor = ""
        }, et = function (t) {
            var e = B().querySelector("#" + I.content);
            t.html ? X(t.html, e) : t.text ? (e.textContent = t.text, z(e)) : W(e)
        }, nt = function (t) {
            for (var e = C(), n = 0; n < e.length; n++) W(e[n]);
            if (t.type) if (-1 !== Object.keys(f).indexOf(t.type)) {
                var o = Et.getPopup().querySelector(".".concat(I.icon, ".").concat(f[t.type]));
                z(o), t.customClass && D(o, t.customClass.icon), t.animation && D(o, "swal2-animate-".concat(t.type, "-icon"))
            } else H('Unknown type! Expected "success", "error", "warning", "info" or "question", got "'.concat(t.type, '"'))
        }, ot = function (t) {
            var e = x();
            t.imageUrl ? (e.setAttribute("src", t.imageUrl), e.setAttribute("alt", t.imageAlt), z(e), t.imageWidth ? e.setAttribute("width", t.imageWidth) : e.removeAttribute("width"), t.imageHeight ? e.setAttribute("height", t.imageHeight) : e.removeAttribute("height"), e.className = I.image, t.imageClass && D(e, t.imageClass), t.customClass && D(e, t.customClass.image)) : W(e)
        }, it = function (i) {
            var r = A(), a = parseInt(null === i.currentProgressStep ? Et.getQueueStep() : i.currentProgressStep, 10);
            i.progressSteps && i.progressSteps.length ? (z(r), r.innerHTML = "", a >= i.progressSteps.length && q("Invalid currentProgressStep parameter, it should be less than progressSteps.length (currentProgressStep like JS arrays starts from 0)"), i.progressSteps.forEach(function (t, e) {
                var n = document.createElement("li");
                if (D(n, I["progress-step"]), n.innerHTML = t, e === a && D(n, I["active-progress-step"]), r.appendChild(n), e !== i.progressSteps.length - 1) {
                    var o = document.createElement("li");
                    D(o, I["progress-step-line"]), i.progressStepsDistance && (o.style.width = i.progressStepsDistance), r.appendChild(o)
                }
            })) : W(r)
        }, rt = function (t) {
            var e = k();
            t.titleText ? e.innerText = t.titleText : t.title && ("string" == typeof t.title && (t.title = t.title.split("\n").join("<br />")), X(t.title, e))
        };
    var at = [], st = function () {
            var t = w();
            t || Et.fire(""), t = w();
            var e = F(), n = S(), o = L();
            z(e), z(n), D([t, e], I.loading), n.disabled = !0, o.disabled = !0, t.setAttribute("data-loading", !0), t.setAttribute("aria-busy", !0), t.focus()
        }, ct = {}, ut = {
            title: "",
            titleText: "",
            text: "",
            html: "",
            footer: "",
            type: null,
            toast: !1,
            customClass: "",
            customContainerClass: "",
            target: "body",
            backdrop: !0,
            animation: !0,
            heightAuto: !0,
            allowOutsideClick: !0,
            allowEscapeKey: !0,
            allowEnterKey: !0,
            stopKeydownPropagation: !0,
            keydownListenerCapture: !1,
            showConfirmButton: !0,
            showCancelButton: !1,
            preConfirm: null,
            confirmButtonText: "OK",
            confirmButtonAriaLabel: "",
            confirmButtonColor: null,
            confirmButtonClass: "",
            cancelButtonText: "Cancel",
            cancelButtonAriaLabel: "",
            cancelButtonColor: null,
            cancelButtonClass: "",
            buttonsStyling: !0,
            reverseButtons: !1,
            focusConfirm: !0,
            focusCancel: !1,
            showCloseButton: !1,
            closeButtonAriaLabel: "Close this dialog",
            showLoaderOnConfirm: !1,
            imageUrl: null,
            imageWidth: null,
            imageHeight: null,
            imageAlt: "",
            imageClass: "",
            timer: null,
            width: null,
            padding: null,
            background: null,
            input: null,
            inputPlaceholder: "",
            inputValue: "",
            inputOptions: {},
            inputAutoTrim: !0,
            inputClass: "",
            inputAttributes: {},
            inputValidator: null,
            validationMessage: null,
            grow: !1,
            position: "center",
            progressSteps: [],
            currentProgressStep: null,
            progressStepsDistance: null,
            onBeforeOpen: null,
            onAfterClose: null,
            onOpen: null,
            onClose: null,
            scrollbarPadding: !0
        }, lt = {
            customContainerClass: "customClass",
            confirmButtonClass: "customClass",
            cancelButtonClass: "customClass",
            imageClass: "customClass",
            inputClass: "customClass"
        },
        dt = ["allowOutsideClick", "allowEnterKey", "backdrop", "focusConfirm", "focusCancel", "heightAuto", "keydownListenerCapture"],
        pt = function (t) {
            return ut.hasOwnProperty(t)
        }, ft = function (t) {
            return lt[t]
        }, mt = Object.freeze({
            isValidParameter: pt,
            isUpdatableParameter: function (t) {
                return -1 !== ["title", "titleText", "text", "html", "type", "showConfirmButton", "showCancelButton", "confirmButtonText", "confirmButtonAriaLabel", "confirmButtonColor", "confirmButtonClass", "cancelButtonText", "cancelButtonAriaLabel", "cancelButtonColor", "cancelButtonClass", "buttonsStyling", "reverseButtons", "imageUrl", "imageWidth", "imageHeigth", "imageAlt", "imageClass", "progressSteps", "currentProgressStep"].indexOf(t)
            },
            isDeprecatedParameter: ft,
            argsToParams: function (n) {
                var o = {};
                switch (V(n[0])) {
                    case"object":
                        s(o, n[0]);
                        break;
                    default:
                        ["title", "html", "type"].forEach(function (t, e) {
                            switch (V(n[e])) {
                                case"string":
                                    o[t] = n[e];
                                    break;
                                case"undefined":
                                    break;
                                default:
                                    H("Unexpected type of ".concat(t, '! Expected "string", got ').concat(V(n[e])))
                            }
                        })
                }
                return o
            },
            isVisible: function () {
                return K(w())
            },
            clickConfirm: function () {
                return S() && S().click()
            },
            clickCancel: function () {
                return L() && L().click()
            },
            getContainer: v,
            getPopup: w,
            getTitle: k,
            getContent: B,
            getImage: x,
            getIcon: function () {
                var t = C().filter(function (t) {
                    return K(t)
                });
                return t.length ? t[0] : null
            },
            getIcons: C,
            getCloseButton: Y,
            getActions: F,
            getConfirmButton: S,
            getCancelButton: L,
            getHeader: Z,
            getFooter: Q,
            getFocusableElements: $,
            getValidationMessage: P,
            isLoading: function () {
                return w().hasAttribute("data-loading")
            },
            fire: function () {
                for (var t = arguments.length, e = new Array(t), n = 0; n < t; n++) e[n] = arguments[n];
                return o(this, e)
            },
            mixin: function (i) {
                return function (t) {
                    function e() {
                        return a(this, e), l(this, c(e).apply(this, arguments))
                    }

                    var n, o;
                    return function (t, e) {
                        if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
                        t.prototype = Object.create(e && e.prototype, {
                            constructor: {
                                value: t,
                                writable: !0,
                                configurable: !0
                            }
                        }), e && u(t, e)
                    }(e, t), r((n = e).prototype, [{
                        key: "_main", value: function (t) {
                            return d(c(e.prototype), "_main", this).call(this, s({}, i, t))
                        }
                    }]), o && r(n, o), e
                }(this)
            },
            queue: function (t) {
                var r = this;
                at = t;
                var a = function () {
                    at = [], document.body.removeAttribute("data-swal2-queue-step")
                }, s = [];
                return new Promise(function (i) {
                    !function e(n, o) {
                        n < at.length ? (document.body.setAttribute("data-swal2-queue-step", n), r.fire(at[n]).then(function (t) {
                            void 0 !== t.value ? (s.push(t.value), e(n + 1, o)) : (a(), i({dismiss: t.dismiss}))
                        })) : (a(), i({value: s}))
                    }(0)
                })
            },
            getQueueStep: function () {
                return document.body.getAttribute("data-swal2-queue-step")
            },
            insertQueueStep: function (t, e) {
                return e && e < at.length ? at.splice(e, 0, t) : at.push(t)
            },
            deleteQueueStep: function (t) {
                void 0 !== at[t] && at.splice(t, 1)
            },
            showLoading: st,
            enableLoading: st,
            getTimerLeft: function () {
                return ct.timeout && ct.timeout.getTimerLeft()
            },
            stopTimer: function () {
                return ct.timeout && ct.timeout.stop()
            },
            resumeTimer: function () {
                return ct.timeout && ct.timeout.start()
            },
            toggleTimer: function () {
                var t = ct.timeout;
                return t && (t.running ? t.stop() : t.start())
            },
            increaseTimer: function (t) {
                return ct.timeout && ct.timeout.increase(t)
            },
            isTimerRunning: function () {
                return ct.timeout && ct.timeout.isRunning()
            }
        }), gt = {promise: new WeakMap, innerParams: new WeakMap, domCache: new WeakMap};

    function ht() {
        var t = gt.innerParams.get(this), e = gt.domCache.get(this);
        t.showConfirmButton || (W(e.confirmButton), t.showCancelButton || W(e.actions)), U([e.popup, e.actions], I.loading), e.popup.removeAttribute("aria-busy"), e.popup.removeAttribute("data-loading"), e.confirmButton.disabled = !1, e.cancelButton.disabled = !1
    }

    var vt = function () {
        null === m.previousBodyPadding && document.body.scrollHeight > window.innerHeight && (m.previousBodyPadding = parseInt(window.getComputedStyle(document.body).getPropertyValue("padding-right")), document.body.style.paddingRight = m.previousBodyPadding + function () {
            if ("ontouchstart" in window || navigator.msMaxTouchPoints) return 0;
            var t = document.createElement("div");
            t.style.width = "50px", t.style.height = "50px", t.style.overflow = "scroll", document.body.appendChild(t);
            var e = t.offsetWidth - t.clientWidth;
            return document.body.removeChild(t), e
        }() + "px")
    }, bt = function () {
        return !!window.MSInputMethodContext && !!document.documentMode
    }, yt = function () {
        var t = v(), e = w();
        t.style.removeProperty("align-items"), e.offsetTop < 0 && (t.style.alignItems = "flex-start")
    }, wt = {swalPromiseResolve: new WeakMap};

    function Ct(t) {
        var e = v(), n = w(), o = gt.innerParams.get(this), i = wt.swalPromiseResolve.get(this), r = o.onClose,
            a = o.onAfterClose;
        if (n) {
            null !== r && "function" == typeof r && r(n), U(n, I.show), D(n, I.hide);
            var s = function () {
                T() ? kt(a) : (new Promise(function (t) {
                    var e = window.scrollX, n = window.scrollY;
                    ct.restoreFocusTimeout = setTimeout(function () {
                        ct.previousActiveElement && ct.previousActiveElement.focus ? (ct.previousActiveElement.focus(), ct.previousActiveElement = null) : document.body && document.body.focus(), t()
                    }, 100), void 0 !== e && void 0 !== n && window.scrollTo(e, n)
                }).then(function () {
                    return kt(a)
                }), ct.keydownTarget.removeEventListener("keydown", ct.keydownHandler, {capture: ct.keydownListenerCapture}), ct.keydownHandlerAdded = !1), e.parentNode && e.parentNode.removeChild(e), U([document.documentElement, document.body], [I.shown, I["height-auto"], I["no-backdrop"], I["toast-shown"], I["toast-column"]]), E() && (null !== m.previousBodyPadding && (document.body.style.paddingRight = m.previousBodyPadding + "px", m.previousBodyPadding = null), function () {
                    if (g(document.body, I.iosfix)) {
                        var t = parseInt(document.body.style.top, 10);
                        U(document.body, I.iosfix), document.body.style.top = "", document.body.scrollTop = -1 * t
                    }
                }(), "undefined" != typeof window && bt() && window.removeEventListener("resize", yt), p(document.body.children).forEach(function (t) {
                    t.hasAttribute("data-previous-aria-hidden") ? (t.setAttribute("aria-hidden", t.getAttribute("data-previous-aria-hidden")), t.removeAttribute("data-previous-aria-hidden")) : t.removeAttribute("aria-hidden")
                }))
            };
            G && !g(n, I.noanimation) ? n.addEventListener(G, function t() {
                n.removeEventListener(G, t), g(n, I.hide) && s()
            }) : s(), i(t || {})
        }
    }

    var kt = function (t) {
        null !== t && "function" == typeof t && setTimeout(function () {
            t()
        })
    };
    var Bt = function t(e, n) {
        a(this, t);
        var o, i, r = n;
        this.running = !1, this.start = function () {
            return this.running || (this.running = !0, i = new Date, o = setTimeout(e, r)), r
        }, this.stop = function () {
            return this.running && (this.running = !1, clearTimeout(o), r -= new Date - i), r
        }, this.increase = function (t) {
            var e = this.running;
            return e && this.stop(), r += t, e && this.start(), r
        }, this.getTimerLeft = function () {
            return this.running && (this.stop(), this.start()), r
        }, this.isRunning = function () {
            return this.running
        }, this.start()
    }, xt = {
        email: function (t, e) {
            return /^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9.-]+\.[a-zA-Z0-9-]{2,24}$/.test(t) ? Promise.resolve() : Promise.resolve(e || "Invalid email address")
        }, url: function (t, e) {

        }
    };
    var At = function (t) {
        var e = v(), n = w();
        null !== t.onBeforeOpen && "function" == typeof t.onBeforeOpen && t.onBeforeOpen(n), t.animation ? (D(n, I.show), D(e, I.fade), U(n, I.hide)) : U(n, I.fade), z(n), e.style.overflowY = "hidden", G && !g(n, I.noanimation) ? n.addEventListener(G, function t() {
            n.removeEventListener(G, t), e.style.overflowY = "auto"
        }) : e.style.overflowY = "auto", D([document.documentElement, document.body, e], I.shown), t.heightAuto && t.backdrop && !t.toast && D([document.documentElement, document.body], I["height-auto"]), E() && (t.scrollbarPadding && vt(), function () {
            if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream && !g(document.body, I.iosfix)) {
                var t = document.body.scrollTop;
                document.body.style.top = -1 * t + "px", D(document.body, I.iosfix)
            }
        }(), "undefined" != typeof window && bt() && (yt(), window.addEventListener("resize", yt)), p(document.body.children).forEach(function (t) {
            t === v() || function (t, e) {
                if ("function" == typeof t.contains) return t.contains(e)
            }(t, v()) || (t.hasAttribute("aria-hidden") && t.setAttribute("data-previous-aria-hidden", t.getAttribute("aria-hidden")), t.setAttribute("aria-hidden", "true"))
        }), setTimeout(function () {
            e.scrollTop = 0
        })), T() || ct.previousActiveElement || (ct.previousActiveElement = document.activeElement), null !== t.onOpen && "function" == typeof t.onOpen && setTimeout(function () {
            t.onOpen(n)
        })
    };
    var Pt, St = Object.freeze({
        hideLoading: ht, disableLoading: ht, getInput: function (t) {
            var e = gt.innerParams.get(this), n = gt.domCache.get(this);
            if (!(t = t || e.input)) return null;
            switch (t) {
                case"select":
                case"textarea":
                case"file":
                    return _(n.content, I[t]);
                case"checkbox":
                    return n.popup.querySelector(".".concat(I.checkbox, " input"));
                case"radio":
                    return n.popup.querySelector(".".concat(I.radio, " input:checked")) || n.popup.querySelector(".".concat(I.radio, " input:first-child"));
                case"range":
                    return n.popup.querySelector(".".concat(I.range, " input"));
                default:
                    return _(n.content, I.input)
            }
        }, close: Ct, closePopup: Ct, closeModal: Ct, closeToast: Ct, enableButtons: function () {
            var t = gt.domCache.get(this);
            t.confirmButton.disabled = !1, t.cancelButton.disabled = !1
        }, disableButtons: function () {
            var t = gt.domCache.get(this);
            t.confirmButton.disabled = !0, t.cancelButton.disabled = !0
        }, enableConfirmButton: function () {
            gt.domCache.get(this).confirmButton.disabled = !1
        }, disableConfirmButton: function () {
            gt.domCache.get(this).confirmButton.disabled = !0
        }, enableInput: function () {
            var t = this.getInput();
            if (!t) return !1;
            if ("radio" === t.type) for (var e = t.parentNode.parentNode.querySelectorAll("input"), n = 0; n < e.length; n++) e[n].disabled = !1; else t.disabled = !1
        }, disableInput: function () {
            var t = this.getInput();
            if (!t) return !1;
            if (t && "radio" === t.type) for (var e = t.parentNode.parentNode.querySelectorAll("input"), n = 0; n < e.length; n++) e[n].disabled = !0; else t.disabled = !0
        }, showValidationMessage: function (t) {
            var e = gt.domCache.get(this);
            e.validationMessage.innerHTML = t;
            var n = window.getComputedStyle(e.popup);
            e.validationMessage.style.marginLeft = "-".concat(n.getPropertyValue("padding-left")), e.validationMessage.style.marginRight = "-".concat(n.getPropertyValue("padding-right")), z(e.validationMessage);
            var o = this.getInput();
            o && (o.setAttribute("aria-invalid", !0), o.setAttribute("aria-describedBy", I["validation-message"]), N(o), D(o, I.inputerror))
        }, resetValidationMessage: function () {
            var t = gt.domCache.get(this);
            t.validationMessage && W(t.validationMessage);
            var e = this.getInput();
            e && (e.removeAttribute("aria-invalid"), e.removeAttribute("aria-describedBy"), U(e, I.inputerror))
        }, getProgressSteps: function () {
            return gt.innerParams.get(this).progressSteps
        }, setProgressSteps: function (t) {
            var e = s({}, gt.innerParams.get(this), {progressSteps: t});
            gt.innerParams.set(this, e), it(e)
        }, showProgressSteps: function () {
            var t = gt.domCache.get(this);
            z(t.progressSteps)
        }, hideProgressSteps: function () {
            var t = gt.domCache.get(this);
            W(t.progressSteps)
        }, _main: function (t) {
            var E = this;
            !function (t) {
                for (var e in t) pt(e) || q('Unknown parameter "'.concat(e, '"')), t.toast && -1 !== dt.indexOf(e) && q('The parameter "'.concat(e, '" is incompatible with toasts')), ft(e) && (n = 'The parameter "'.concat(e, '" is deprecated and will be removed in the next major release. Please use "').concat(ft(e), '" instead.'), -1 === i.indexOf(n) && (i.push(n), q(n)));
                var n
            }(t);
            var T = s({}, ut, t);
            !function (e) {
                var t;
                e.inputValidator || Object.keys(xt).forEach(function (t) {
                    e.input === t && (e.inputValidator = xt[t])
                }), (!e.target || "string" == typeof e.target && !document.querySelector(e.target) || "string" != typeof e.target && !e.target.appendChild) && (q('Target parameter is not valid, defaulting to "body"'), e.target = "body"), "function" == typeof e.animation && (e.animation = e.animation.call());
                var n = w(), o = "string" == typeof e.target ? document.querySelector(e.target) : e.target;
                t = n && o && n.parentNode !== o.parentNode ? J(e) : n || J(e), e.width && (t.style.width = "number" == typeof e.width ? e.width + "px" : e.width), null !== e.padding && (t.style.padding = "number" == typeof e.padding ? e.padding + "px" : e.padding), e.background && (t.style.background = e.background);
                for (var i = window.getComputedStyle(t).getPropertyValue("background-color"), r = t.querySelectorAll("[class^=swal2-success-circular-line], .swal2-success-fix"), a = 0; a < r.length; a++) r[a].style.backgroundColor = i;
                var s = v(), c = Y(), u = Z(), l = k(), d = B(), p = F(), f = Q();
                if (rt(e), et(e), "string" == typeof e.backdrop ? v().style.background = e.backdrop : e.backdrop || D([document.documentElement, document.body], I["no-backdrop"]), !e.backdrop && e.allowOutsideClick && q('"allowOutsideClick" parameter requires `backdrop` parameter to be set to `true`'), e.position in I ? D(s, I[e.position]) : (q('The "position" parameter is not valid, defaulting to "center"'), D(s, I.center)), e.grow && "string" == typeof e.grow) {
                    var m = "grow-" + e.grow;
                    m in I && D(s, I[m])
                }
                e.showCloseButton ? (c.setAttribute("aria-label", e.closeButtonAriaLabel), z(c)) : W(c), t.className = I.popup, e.toast ? (D([document.documentElement, document.body], I["toast-shown"]), D(t, I.toast)) : D(t, I.modal), e.customClass && (D(s, e.customClass.container), D(t, "string" == typeof e.customClass ? e.customClass : e.customClass.popup), D(u, e.customClass.header), D(l, e.customClass.title), D(c, e.customClass.closeButton), D(d, e.customClass.content), D(p, e.customClass.actions), D(f, e.customClass.footer)), e.customContainerClass && D(s, e.customContainerClass), it(e), nt(e), ot(e), tt(e), X(e.footer, f), !0 === e.animation ? U(t, I.noanimation) : D(t, I.noanimation), e.showLoaderOnConfirm && !e.preConfirm && q("showLoaderOnConfirm is set to true, but preConfirm is not defined.\nshowLoaderOnConfirm should be used together with preConfirm, see usage example:\nhttps://sweetalert2.github.io/#ajax-request")
            }(T), Object.freeze(T), gt.innerParams.set(this, T), ct.timeout && (ct.timeout.stop(), delete ct.timeout), clearTimeout(ct.restoreFocusTimeout);
            var O = {
                popup: w(),
                container: v(),
                content: B(),
                actions: F(),
                confirmButton: S(),
                cancelButton: L(),
                closeButton: Y(),
                validationMessage: P(),
                progressSteps: A()
            };
            gt.domCache.set(this, O);
            var M = this.constructor;
            return new Promise(function (t) {
                var n = function (t) {
                    E.closePopup({value: t})
                }, s = function (t) {
                    E.closePopup({dismiss: t})
                };
                wt.swalPromiseResolve.set(E, t), T.timer && (ct.timeout = new Bt(function () {
                    s("timer"), delete ct.timeout
                }, T.timer)), T.input && setTimeout(function () {
                    var t = E.getInput();
                    t && N(t)
                }, 0);
                for (var c = function (e) {
                    T.showLoaderOnConfirm && M.showLoading(), T.preConfirm ? (E.resetValidationMessage(), Promise.resolve().then(function () {
                        return T.preConfirm(e, T.validationMessage)
                    }).then(function (t) {
                        K(O.validationMessage) || !1 === t ? E.hideLoading() : n(void 0 === t ? e : t)
                    })) : n(e)
                }, e = function (t) {
                    var e = t.target, n = O.confirmButton, o = O.cancelButton, i = n && (n === e || n.contains(e)),
                        r = o && (o === e || o.contains(e));
                    switch (t.type) {
                        case"click":
                            if (i) if (E.disableButtons(), T.input) {
                                var a = function () {
                                    var t = E.getInput();
                                    if (!t) return null;
                                    switch (T.input) {
                                        case"checkbox":
                                            return t.checked ? 1 : 0;
                                        case"radio":
                                            return t.checked ? t.value : null;
                                        case"file":
                                            return t.files.length ? t.files[0] : null;
                                        default:
                                            return T.inputAutoTrim ? t.value.trim() : t.value
                                    }
                                }();
                                T.inputValidator ? (E.disableInput(), Promise.resolve().then(function () {
                                    return T.inputValidator(a, T.validationMessage)
                                }).then(function (t) {
                                    E.enableButtons(), E.enableInput(), t ? E.showValidationMessage(t) : c(a)
                                })) : E.getInput().checkValidity() ? c(a) : (E.enableButtons(), E.showValidationMessage(T.validationMessage))
                            } else c(!0); else r && (E.disableButtons(), s(M.DismissReason.cancel))
                    }
                }, o = O.popup.querySelectorAll("button"), i = 0; i < o.length; i++) o[i].onclick = e, o[i].onmouseover = e, o[i].onmouseout = e, o[i].onmousedown = e;
                if (O.closeButton.onclick = function () {
                    s(M.DismissReason.close)
                }, T.toast) O.popup.onclick = function () {
                    T.showConfirmButton || T.showCancelButton || T.showCloseButton || T.input || s(M.DismissReason.close)
                }; else {
                    var r = !1;
                    O.popup.onmousedown = function () {
                        O.container.onmouseup = function (t) {
                            O.container.onmouseup = void 0, t.target === O.container && (r = !0)
                        }
                    }, O.container.onmousedown = function () {
                        O.popup.onmouseup = function (t) {
                            O.popup.onmouseup = void 0, (t.target === O.popup || O.popup.contains(t.target)) && (r = !0)
                        }
                    }, O.container.onclick = function (t) {
                        r ? r = !1 : t.target === O.container && j(T.allowOutsideClick) && s(M.DismissReason.backdrop)
                    }
                }
                T.reverseButtons ? O.confirmButton.parentNode.insertBefore(O.cancelButton, O.confirmButton) : O.confirmButton.parentNode.insertBefore(O.confirmButton, O.cancelButton);
                var a = function (t, e) {
                    for (var n = $(T.focusCancel), o = 0; o < n.length; o++) return (t += e) === n.length ? t = 0 : -1 === t && (t = n.length - 1), n[t].focus();
                    O.popup.focus()
                };
                ct.keydownHandlerAdded && (ct.keydownTarget.removeEventListener("keydown", ct.keydownHandler, {capture: ct.keydownListenerCapture}), ct.keydownHandlerAdded = !1), T.toast || (ct.keydownHandler = function (t) {
                    return function (t, e) {
                        if (e.stopKeydownPropagation && t.stopPropagation(), "Enter" !== t.key || t.isComposing) if ("Tab" === t.key) {
                            for (var n = t.target, o = $(e.focusCancel), i = -1, r = 0; r < o.length; r++) if (n === o[r]) {
                                i = r;
                                break
                            }
                            t.shiftKey ? a(i, -1) : a(i, 1), t.stopPropagation(), t.preventDefault()
                        } else -1 !== ["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Left", "Right", "Up", "Down"].indexOf(t.key) ? document.activeElement === O.confirmButton && K(O.cancelButton) ? O.cancelButton.focus() : document.activeElement === O.cancelButton && K(O.confirmButton) && O.confirmButton.focus() : "Escape" !== t.key && "Esc" !== t.key || !0 !== j(e.allowEscapeKey) || (t.preventDefault(), s(M.DismissReason.esc)); else if (t.target && E.getInput() && t.target.outerHTML === E.getInput().outerHTML) {
                            if (-1 !== ["textarea", "file"].indexOf(e.input)) return;
                            M.clickConfirm(), t.preventDefault()
                        }
                    }(t, T)
                }, ct.keydownTarget = T.keydownListenerCapture ? window : O.popup, ct.keydownListenerCapture = T.keydownListenerCapture, ct.keydownTarget.addEventListener("keydown", ct.keydownHandler, {capture: ct.keydownListenerCapture}), ct.keydownHandlerAdded = !0), E.enableButtons(), E.hideLoading(), E.resetValidationMessage(), T.toast && (T.input || T.footer || T.showCloseButton) ? D(document.body, I["toast-column"]) : U(document.body, I["toast-column"]);
                for (var u, l, d = ["input", "file", "range", "select", "radio", "checkbox", "textarea"], p = function (t) {
                    t.placeholder && !T.inputPlaceholder || (t.placeholder = T.inputPlaceholder)
                }, f = 0; f < d.length; f++) {
                    var m = I[d[f]], g = _(O.content, m);
                    if (u = E.getInput(d[f])) {
                        for (var h in u.attributes) if (u.attributes.hasOwnProperty(h)) {
                            var v = u.attributes[h].name;
                            "type" !== v && "value" !== v && u.removeAttribute(v)
                        }
                        for (var b in T.inputAttributes) "range" === d[f] && "placeholder" === b || u.setAttribute(b, T.inputAttributes[b])
                    }
                    g.className = m, T.inputClass && D(g, T.inputClass), T.customClass && D(g, T.customClass.input), W(g)
                }
                switch (T.input) {
                    case"text":
                    case"email":
                    case"password":
                    case"number":
                    case"tel":
                    case"url":
                        u = _(O.content, I.input), "string" == typeof T.inputValue || "number" == typeof T.inputValue ? u.value = T.inputValue : R(T.inputValue) || q('Unexpected type of inputValue! Expected "string", "number" or "Promise", got "'.concat(V(T.inputValue), '"')), p(u), u.type = T.input, z(u);
                        break;
                    case"file":
                        p(u = _(O.content, I.file)), u.type = T.input, z(u);
                        break;
                    case"range":
                        var y = _(O.content, I.range), w = y.querySelector("input"), C = y.querySelector("output");
                        w.value = T.inputValue, w.type = T.input, C.value = T.inputValue, z(y);
                        break;
                    case"select":
                        var k = _(O.content, I.select);
                        if (k.innerHTML = "", T.inputPlaceholder) {
                            var B = document.createElement("option");
                            B.innerHTML = T.inputPlaceholder, B.value = "", B.disabled = !0, B.selected = !0, k.appendChild(B)
                        }
                        l = function (t) {
                            t.forEach(function (t) {
                                var e = t[0], n = t[1], o = document.createElement("option");
                                o.value = e, o.innerHTML = n, T.inputValue.toString() === e.toString() && (o.selected = !0), k.appendChild(o)
                            }), z(k), k.focus()
                        };
                        break;
                    case"radio":
                        var x = _(O.content, I.radio);
                        x.innerHTML = "", l = function (t) {
                            t.forEach(function (t) {
                                var e = t[0], n = t[1], o = document.createElement("input"),
                                    i = document.createElement("label");
                                o.type = "radio", o.name = I.radio, o.value = e, T.inputValue.toString() === e.toString() && (o.checked = !0);
                                var r = document.createElement("span");
                                r.innerHTML = n, r.className = I.label, i.appendChild(o), i.appendChild(r), x.appendChild(i)
                            }), z(x);
                            var e = x.querySelectorAll("input");
                            e.length && e[0].focus()
                        };
                        break;
                    case"checkbox":
                        var A = _(O.content, I.checkbox), P = E.getInput("checkbox");
                        P.type = "checkbox", P.value = 1, P.id = I.checkbox, P.checked = Boolean(T.inputValue), A.querySelector("span").innerHTML = T.inputPlaceholder, z(A);
                        break;
                    case"textarea":
                        var S = _(O.content, I.textarea);
                        S.value = T.inputValue, p(S), z(S);
                        break;
                    case null:
                        break;
                    default:
                        H('Unexpected type of input! Expected "text", "email", "password", "number", "tel", "select", "radio", "checkbox", "textarea", "file" or "url", got "'.concat(T.input, '"'))
                }
                if ("select" === T.input || "radio" === T.input) {
                    var L = function (t) {
                        return l((e = t, n = [], "undefined" != typeof Map && e instanceof Map ? e.forEach(function (t, e) {
                            n.push([e, t])
                        }) : Object.keys(e).forEach(function (t) {
                            n.push([t, e[t]])
                        }), n));
                        var e, n
                    };
                    R(T.inputOptions) ? (M.showLoading(), T.inputOptions.then(function (t) {
                        E.hideLoading(), L(t)
                    })) : "object" === V(T.inputOptions) ? L(T.inputOptions) : H("Unexpected type of inputOptions! Expected object, Map or Promise, got ".concat(V(T.inputOptions)))
                } else -1 !== ["text", "email", "number", "tel", "textarea"].indexOf(T.input) && R(T.inputValue) && (M.showLoading(), W(u), T.inputValue.then(function (t) {
                    u.value = "number" === T.input ? parseFloat(t) || 0 : t + "", z(u), u.focus(), E.hideLoading()
                }).catch(function (t) {
                    H("Error in inputValue promise: " + t), u.value = "", z(u), u.focus(), E.hideLoading()
                }));
                At(T), T.toast || (j(T.allowEnterKey) ? T.focusCancel && K(O.cancelButton) ? O.cancelButton.focus() : T.focusConfirm && K(O.confirmButton) ? O.confirmButton.focus() : a(-1, 1) : document.activeElement && "function" == typeof document.activeElement.blur && document.activeElement.blur()), O.container.scrollTop = 0
            })
        }, update: function (e) {
            var n = {};
            Object.keys(e).forEach(function (t) {
                Et.isUpdatableParameter(t) ? n[t] = e[t] : q('Invalid parameter to update: "'.concat(t, '". Updatable params are listed here: https://github.com/sweetalert2/sweetalert2/blob/master/src/utils/params.js'))
            });
            var t = s({}, gt.innerParams.get(this), n);
            tt(t), et(t), nt(t), ot(t), it(t), rt(t), gt.innerParams.set(this, t)
        }
    });

    function Lt() {
        if ("undefined" != typeof window) {
            "undefined" == typeof Promise && H("This package requires a Promise library, please include a shim to enable it in this browser (See: https://github.com/sweetalert2/sweetalert2/wiki/Migration-from-SweetAlert-to-SweetAlert2#1-ie-support)"), Pt = this;
            for (var t = arguments.length, e = new Array(t), n = 0; n < t; n++) e[n] = arguments[n];
            var o = Object.freeze(this.constructor.argsToParams(e));
            Object.defineProperties(this, {params: {value: o, writable: !1, enumerable: !0}});
            var i = this._main(this.params);
            gt.promise.set(this, i)
        }
    }

    Lt.prototype.then = function (t) {
        return gt.promise.get(this).then(t)
    }, Lt.prototype.finally = function (t) {
        return gt.promise.get(this).finally(t)
    }, s(Lt.prototype, St), s(Lt, mt), Object.keys(St).forEach(function (e) {
        Lt[e] = function () {
            var t;
            if (Pt) return (t = Pt)[e].apply(t, arguments)
        }
    }), Lt.DismissReason = t, Lt.version = "8.5.0";
    var Et = Lt;
    return Et.default = Et
}), "undefined" != typeof window && window.Sweetalert2 && (window.swal = window.sweetAlert = window.Swal = window.SweetAlert = window.Sweetalert2);
"undefined" != typeof document && function (e, t) {
    var n = e.createElement("style");
    if (e.getElementsByTagName("head")[0].appendChild(n), n.styleSheet) n.styleSheet.disabled || (n.styleSheet.cssText = t); else try {
        n.innerHTML = t
    } catch (e) {
        n.innerText = t
    }
}(document, "@charset \"UTF-8\";@-webkit-keyframes swal2-show{0%{-webkit-transform:scale(.7);transform:scale(.7)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}100%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes swal2-show{0%{-webkit-transform:scale(.7);transform:scale(.7)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}100%{-webkit-transform:scale(1);transform:scale(1)}}@-webkit-keyframes swal2-hide{0%{-webkit-transform:scale(1);transform:scale(1);opacity:1}100%{-webkit-transform:scale(.5);transform:scale(.5);opacity:0}}@keyframes swal2-hide{0%{-webkit-transform:scale(1);transform:scale(1);opacity:1}100%{-webkit-transform:scale(.5);transform:scale(.5);opacity:0}}@-webkit-keyframes swal2-animate-success-line-tip{0%{top:1.1875em;left:.0625em;width:0}54%{top:1.0625em;left:.125em;width:0}70%{top:2.1875em;left:-.375em;width:3.125em}84%{top:3em;left:1.3125em;width:1.0625em}100%{top:2.8125em;left:.875em;width:1.5625em}}@keyframes swal2-animate-success-line-tip{0%{top:1.1875em;left:.0625em;width:0}54%{top:1.0625em;left:.125em;width:0}70%{top:2.1875em;left:-.375em;width:3.125em}84%{top:3em;left:1.3125em;width:1.0625em}100%{top:2.8125em;left:.875em;width:1.5625em}}@-webkit-keyframes swal2-animate-success-line-long{0%{top:3.375em;right:2.875em;width:0}65%{top:3.375em;right:2.875em;width:0}84%{top:2.1875em;right:0;width:3.4375em}100%{top:2.375em;right:.5em;width:2.9375em}}@keyframes swal2-animate-success-line-long{0%{top:3.375em;right:2.875em;width:0}65%{top:3.375em;right:2.875em;width:0}84%{top:2.1875em;right:0;width:3.4375em}100%{top:2.375em;right:.5em;width:2.9375em}}@-webkit-keyframes swal2-rotate-success-circular-line{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}100%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@keyframes swal2-rotate-success-circular-line{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}100%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@-webkit-keyframes swal2-animate-error-x-mark{0%{margin-top:1.625em;-webkit-transform:scale(.4);transform:scale(.4);opacity:0}50%{margin-top:1.625em;-webkit-transform:scale(.4);transform:scale(.4);opacity:0}80%{margin-top:-.375em;-webkit-transform:scale(1.15);transform:scale(1.15)}100%{margin-top:0;-webkit-transform:scale(1);transform:scale(1);opacity:1}}@keyframes swal2-animate-error-x-mark{0%{margin-top:1.625em;-webkit-transform:scale(.4);transform:scale(.4);opacity:0}50%{margin-top:1.625em;-webkit-transform:scale(.4);transform:scale(.4);opacity:0}80%{margin-top:-.375em;-webkit-transform:scale(1.15);transform:scale(1.15)}100%{margin-top:0;-webkit-transform:scale(1);transform:scale(1);opacity:1}}@-webkit-keyframes swal2-animate-error-icon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}100%{-webkit-transform:rotateX(0);transform:rotateX(0);opacity:1}}@keyframes swal2-animate-error-icon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}100%{-webkit-transform:rotateX(0);transform:rotateX(0);opacity:1}}body.swal2-toast-shown .swal2-container{background-color:transparent}body.swal2-toast-shown .swal2-container.swal2-shown{background-color:transparent}body.swal2-toast-shown .swal2-container.swal2-top{top:0;right:auto;bottom:auto;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%)}body.swal2-toast-shown .swal2-container.swal2-top-end,body.swal2-toast-shown .swal2-container.swal2-top-right{top:0;right:0;bottom:auto;left:auto}body.swal2-toast-shown .swal2-container.swal2-top-left,body.swal2-toast-shown .swal2-container.swal2-top-start{top:0;right:auto;bottom:auto;left:0}body.swal2-toast-shown .swal2-container.swal2-center-left,body.swal2-toast-shown .swal2-container.swal2-center-start{top:50%;right:auto;bottom:auto;left:0;-webkit-transform:translateY(-50%);transform:translateY(-50%)}body.swal2-toast-shown .swal2-container.swal2-center{top:50%;right:auto;bottom:auto;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}body.swal2-toast-shown .swal2-container.swal2-center-end,body.swal2-toast-shown .swal2-container.swal2-center-right{top:50%;right:0;bottom:auto;left:auto;-webkit-transform:translateY(-50%);transform:translateY(-50%)}body.swal2-toast-shown .swal2-container.swal2-bottom-left,body.swal2-toast-shown .swal2-container.swal2-bottom-start{top:auto;right:auto;bottom:0;left:0}body.swal2-toast-shown .swal2-container.swal2-bottom{top:auto;right:auto;bottom:0;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%)}body.swal2-toast-shown .swal2-container.swal2-bottom-end,body.swal2-toast-shown .swal2-container.swal2-bottom-right{top:auto;right:0;bottom:0;left:auto}body.swal2-toast-column .swal2-toast{flex-direction:column;align-items:stretch}body.swal2-toast-column .swal2-toast .swal2-actions{flex:1;align-self:stretch;height:2.2em;margin-top:.3125em}body.swal2-toast-column .swal2-toast .swal2-loading{justify-content:center}body.swal2-toast-column .swal2-toast .swal2-input{height:2em;margin:.3125em auto;font-size:1em}body.swal2-toast-column .swal2-toast .swal2-validation-message{font-size:1em}.swal2-popup.swal2-toast{flex-direction:row;align-items:center;width:auto;padding:.625em;box-shadow:0 0 .625em #d9d9d9;overflow-y:hidden}.swal2-popup.swal2-toast .swal2-header{flex-direction:row}.swal2-popup.swal2-toast .swal2-title{flex-grow:1;justify-content:flex-start;margin:0 .6em;font-size:1em}.swal2-popup.swal2-toast .swal2-footer{margin:.5em 0 0;padding:.5em 0 0;font-size:.8em}.swal2-popup.swal2-toast .swal2-close{position:initial;width:.8em;height:.8em;line-height:.8}.swal2-popup.swal2-toast .swal2-content{justify-content:flex-start;font-size:1em}.swal2-popup.swal2-toast .swal2-icon{width:2em;min-width:2em;height:2em;margin:0}.swal2-popup.swal2-toast .swal2-icon::before{display:flex;align-items:center;font-size:2em;font-weight:700}@media all and (-ms-high-contrast:none),(-ms-high-contrast:active){.swal2-popup.swal2-toast .swal2-icon::before{font-size:.25em}}.swal2-popup.swal2-toast .swal2-icon.swal2-success .swal2-success-ring{width:2em;height:2em}.swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line]{top:.875em;width:1.375em}.swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=left]{left:.3125em}.swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=right]{right:.3125em}.swal2-popup.swal2-toast .swal2-actions{height:auto;margin:0 .3125em}.swal2-popup.swal2-toast .swal2-styled{margin:0 .3125em;padding:.3125em .625em;font-size:1em}.swal2-popup.swal2-toast .swal2-styled:focus{box-shadow:0 0 0 .0625em #fff,0 0 0 .125em rgba(50,100,150,.4)}.swal2-popup.swal2-toast .swal2-success{border-color:#a5dc86}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line]{position:absolute;width:2em;height:2.8125em;-webkit-transform:rotate(45deg);transform:rotate(45deg);border-radius:50%}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line][class$=left]{top:-.25em;left:-.9375em;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:2em 2em;transform-origin:2em 2em;border-radius:4em 0 0 4em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line][class$=right]{top:-.25em;left:.9375em;-webkit-transform-origin:0 2em;transform-origin:0 2em;border-radius:0 4em 4em 0}.swal2-popup.swal2-toast .swal2-success .swal2-success-ring{width:2em;height:2em}.swal2-popup.swal2-toast .swal2-success .swal2-success-fix{top:0;left:.4375em;width:.4375em;height:2.6875em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line]{height:.3125em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line][class$=tip]{top:1.125em;left:.1875em;width:.75em}.swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line][class$=long]{top:.9375em;right:.1875em;width:1.375em}.swal2-popup.swal2-toast.swal2-show{-webkit-animation:showSweetToast .5s;animation:showSweetToast .5s}.swal2-popup.swal2-toast.swal2-hide{-webkit-animation:hideSweetToast .2s forwards;animation:hideSweetToast .2s forwards}.swal2-popup.swal2-toast .swal2-animate-success-icon .swal2-success-line-tip{-webkit-animation:animate-toast-success-tip .75s;animation:animate-toast-success-tip .75s}.swal2-popup.swal2-toast .swal2-animate-success-icon .swal2-success-line-long{-webkit-animation:animate-toast-success-long .75s;animation:animate-toast-success-long .75s}@-webkit-keyframes showSweetToast{0%{-webkit-transform:translateY(-.625em) rotateZ(2deg);transform:translateY(-.625em) rotateZ(2deg);opacity:0}33%{-webkit-transform:translateY(0) rotateZ(-2deg);transform:translateY(0) rotateZ(-2deg);opacity:.5}66%{-webkit-transform:translateY(.3125em) rotateZ(2deg);transform:translateY(.3125em) rotateZ(2deg);opacity:.7}100%{-webkit-transform:translateY(0) rotateZ(0);transform:translateY(0) rotateZ(0);opacity:1}}@keyframes showSweetToast{0%{-webkit-transform:translateY(-.625em) rotateZ(2deg);transform:translateY(-.625em) rotateZ(2deg);opacity:0}33%{-webkit-transform:translateY(0) rotateZ(-2deg);transform:translateY(0) rotateZ(-2deg);opacity:.5}66%{-webkit-transform:translateY(.3125em) rotateZ(2deg);transform:translateY(.3125em) rotateZ(2deg);opacity:.7}100%{-webkit-transform:translateY(0) rotateZ(0);transform:translateY(0) rotateZ(0);opacity:1}}@-webkit-keyframes hideSweetToast{0%{opacity:1}33%{opacity:.5}100%{-webkit-transform:rotateZ(1deg);transform:rotateZ(1deg);opacity:0}}@keyframes hideSweetToast{0%{opacity:1}33%{opacity:.5}100%{-webkit-transform:rotateZ(1deg);transform:rotateZ(1deg);opacity:0}}@-webkit-keyframes animate-toast-success-tip{0%{top:.5625em;left:.0625em;width:0}54%{top:.125em;left:.125em;width:0}70%{top:.625em;left:-.25em;width:1.625em}84%{top:1.0625em;left:.75em;width:.5em}100%{top:1.125em;left:.1875em;width:.75em}}@keyframes animate-toast-success-tip{0%{top:.5625em;left:.0625em;width:0}54%{top:.125em;left:.125em;width:0}70%{top:.625em;left:-.25em;width:1.625em}84%{top:1.0625em;left:.75em;width:.5em}100%{top:1.125em;left:.1875em;width:.75em}}@-webkit-keyframes animate-toast-success-long{0%{top:1.625em;right:1.375em;width:0}65%{top:1.25em;right:.9375em;width:0}84%{top:.9375em;right:0;width:1.125em}100%{top:.9375em;right:.1875em;width:1.375em}}@keyframes animate-toast-success-long{0%{top:1.625em;right:1.375em;width:0}65%{top:1.25em;right:.9375em;width:0}84%{top:.9375em;right:0;width:1.125em}100%{top:.9375em;right:.1875em;width:1.375em}}body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown){overflow:hidden}body.swal2-height-auto{height:auto!important}body.swal2-no-backdrop .swal2-shown{top:auto;right:auto;bottom:auto;left:auto;background-color:transparent}body.swal2-no-backdrop .swal2-shown>.swal2-modal{box-shadow:0 0 10px rgba(0,0,0,.4)}body.swal2-no-backdrop .swal2-shown.swal2-top{top:0;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%)}body.swal2-no-backdrop .swal2-shown.swal2-top-left,body.swal2-no-backdrop .swal2-shown.swal2-top-start{top:0;left:0}body.swal2-no-backdrop .swal2-shown.swal2-top-end,body.swal2-no-backdrop .swal2-shown.swal2-top-right{top:0;right:0}body.swal2-no-backdrop .swal2-shown.swal2-center{top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}body.swal2-no-backdrop .swal2-shown.swal2-center-left,body.swal2-no-backdrop .swal2-shown.swal2-center-start{top:50%;left:0;-webkit-transform:translateY(-50%);transform:translateY(-50%)}body.swal2-no-backdrop .swal2-shown.swal2-center-end,body.swal2-no-backdrop .swal2-shown.swal2-center-right{top:50%;right:0;-webkit-transform:translateY(-50%);transform:translateY(-50%)}body.swal2-no-backdrop .swal2-shown.swal2-bottom{bottom:0;left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%)}body.swal2-no-backdrop .swal2-shown.swal2-bottom-left,body.swal2-no-backdrop .swal2-shown.swal2-bottom-start{bottom:0;left:0}body.swal2-no-backdrop .swal2-shown.swal2-bottom-end,body.swal2-no-backdrop .swal2-shown.swal2-bottom-right{right:0;bottom:0}.swal2-container{display:flex;position:fixed;top:0;right:0;bottom:0;left:0;flex-direction:row;align-items:center;justify-content:center;padding:10px;background-color:transparent;z-index:1060;overflow-x:hidden;-webkit-overflow-scrolling:touch}.swal2-container.swal2-top{align-items:flex-start}.swal2-container.swal2-top-left,.swal2-container.swal2-top-start{align-items:flex-start;justify-content:flex-start}.swal2-container.swal2-top-end,.swal2-container.swal2-top-right{align-items:flex-start;justify-content:flex-end}.swal2-container.swal2-center{align-items:center}.swal2-container.swal2-center-left,.swal2-container.swal2-center-start{align-items:center;justify-content:flex-start}.swal2-container.swal2-center-end,.swal2-container.swal2-center-right{align-items:center;justify-content:flex-end}.swal2-container.swal2-bottom{align-items:flex-end}.swal2-container.swal2-bottom-left,.swal2-container.swal2-bottom-start{align-items:flex-end;justify-content:flex-start}.swal2-container.swal2-bottom-end,.swal2-container.swal2-bottom-right{align-items:flex-end;justify-content:flex-end}.swal2-container.swal2-bottom-end>:first-child,.swal2-container.swal2-bottom-left>:first-child,.swal2-container.swal2-bottom-right>:first-child,.swal2-container.swal2-bottom-start>:first-child,.swal2-container.swal2-bottom>:first-child{margin-top:auto}.swal2-container.swal2-grow-fullscreen>.swal2-modal{display:flex!important;flex:1;align-self:stretch;justify-content:center}.swal2-container.swal2-grow-row>.swal2-modal{display:flex!important;flex:1;align-content:center;justify-content:center}.swal2-container.swal2-grow-column{flex:1;flex-direction:column}.swal2-container.swal2-grow-column.swal2-bottom,.swal2-container.swal2-grow-column.swal2-center,.swal2-container.swal2-grow-column.swal2-top{align-items:center}.swal2-container.swal2-grow-column.swal2-bottom-left,.swal2-container.swal2-grow-column.swal2-bottom-start,.swal2-container.swal2-grow-column.swal2-center-left,.swal2-container.swal2-grow-column.swal2-center-start,.swal2-container.swal2-grow-column.swal2-top-left,.swal2-container.swal2-grow-column.swal2-top-start{align-items:flex-start}.swal2-container.swal2-grow-column.swal2-bottom-end,.swal2-container.swal2-grow-column.swal2-bottom-right,.swal2-container.swal2-grow-column.swal2-center-end,.swal2-container.swal2-grow-column.swal2-center-right,.swal2-container.swal2-grow-column.swal2-top-end,.swal2-container.swal2-grow-column.swal2-top-right{align-items:flex-end}.swal2-container.swal2-grow-column>.swal2-modal{display:flex!important;flex:1;align-content:center;justify-content:center}.swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal{margin:auto}@media all and (-ms-high-contrast:none),(-ms-high-contrast:active){.swal2-container .swal2-modal{margin:0!important}}.swal2-container.swal2-fade{transition:background-color .1s}.swal2-container.swal2-shown{background-color:rgba(0,0,0,.4)}.swal2-popup{display:none;position:relative;flex-direction:column;justify-content:center;width:32em;max-width:100%;padding:1.25em;border-radius:.3125em;background:#fff;font-family:inherit;box-sizing:border-box}.swal2-popup:focus{outline:0}.swal2-popup.swal2-loading{overflow-y:hidden}.swal2-popup .swal2-header{display:flex;flex-direction:column;align-items:center}.swal2-popup .swal2-title{display:block;position:relative;max-width:100%;margin:0 0 .4em;padding:0;color:#595959;font-size:1.875em;font-weight:600;text-align:center;text-transform:none;word-wrap:break-word}.swal2-popup .swal2-actions{flex-wrap:wrap;align-items:center;justify-content:center;margin:1.25em auto 0;z-index:1}.swal2-popup .swal2-actions:not(.swal2-loading) .swal2-styled[disabled]{opacity:.4}.swal2-popup .swal2-actions:not(.swal2-loading) .swal2-styled:hover{background-image:linear-gradient(rgba(0,0,0,.1),rgba(0,0,0,.1))}.swal2-popup .swal2-actions:not(.swal2-loading) .swal2-styled:active{background-image:linear-gradient(rgba(0,0,0,.2),rgba(0,0,0,.2))}.swal2-popup .swal2-actions.swal2-loading .swal2-styled.swal2-confirm{width:2.5em;height:2.5em;margin:.46875em;padding:0;border:.25em solid transparent;border-radius:100%;border-color:transparent;background-color:transparent!important;color:transparent;cursor:default;box-sizing:border-box;-webkit-animation:swal2-rotate-loading 1.5s linear 0s infinite normal;animation:swal2-rotate-loading 1.5s linear 0s infinite normal;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.swal2-popup .swal2-actions.swal2-loading .swal2-styled.swal2-cancel{margin-right:30px;margin-left:30px}.swal2-popup .swal2-actions.swal2-loading :not(.swal2-styled).swal2-confirm::after{display:inline-block;width:15px;height:15px;margin-left:5px;border:3px solid #999;border-radius:50%;border-right-color:transparent;box-shadow:1px 1px 1px #fff;content:'';-webkit-animation:swal2-rotate-loading 1.5s linear 0s infinite normal;animation:swal2-rotate-loading 1.5s linear 0s infinite normal}.swal2-popup .swal2-styled{margin:.3125em;padding:.625em 2em;font-weight:500;box-shadow:none}.swal2-popup .swal2-styled:not([disabled]){cursor:pointer}.swal2-popup .swal2-styled.swal2-confirm{border:0;border-radius:.25em;background:initial;background-color:#3085d6;color:#fff;font-size:1.0625em}.swal2-popup .swal2-styled.swal2-cancel{border:0;border-radius:.25em;background:initial;background-color:#aaa;color:#fff;font-size:1.0625em}.swal2-popup .swal2-styled:focus{outline:0;box-shadow:0 0 0 2px #fff,0 0 0 4px rgba(50,100,150,.4)}.swal2-popup .swal2-styled::-moz-focus-inner{border:0}.swal2-popup .swal2-footer{justify-content:center;margin:1.25em 0 0;padding:1em 0 0;border-top:1px solid #eee;color:#545454;font-size:1em}.swal2-popup .swal2-image{max-width:100%;margin:1.25em auto}.swal2-popup .swal2-close{position:absolute;top:0;right:0;justify-content:center;width:1.2em;height:1.2em;padding:0;transition:color .1s ease-out;border:none;border-radius:0;outline:initial;background:0 0;color:#ccc;font-family:serif;font-size:2.5em;line-height:1.2;cursor:pointer;overflow:hidden}.swal2-popup .swal2-close:hover{-webkit-transform:none;transform:none;color:#f27474}.swal2-popup>.swal2-checkbox,.swal2-popup>.swal2-file,.swal2-popup>.swal2-input,.swal2-popup>.swal2-radio,.swal2-popup>.swal2-select,.swal2-popup>.swal2-textarea{display:none}.swal2-popup .swal2-content{justify-content:center;margin:0;padding:0;color:#545454;font-size:1.125em;font-weight:300;line-height:normal;z-index:1;word-wrap:break-word}.swal2-popup #swal2-content{text-align:center}.swal2-popup .swal2-checkbox,.swal2-popup .swal2-file,.swal2-popup .swal2-input,.swal2-popup .swal2-radio,.swal2-popup .swal2-select,.swal2-popup .swal2-textarea{margin:1em auto}.swal2-popup .swal2-file,.swal2-popup .swal2-input,.swal2-popup .swal2-textarea{width:100%;transition:border-color .3s,box-shadow .3s;border:1px solid #d9d9d9;border-radius:.1875em;background:inherit;font-size:1.125em;box-shadow:inset 0 1px 1px rgba(0,0,0,.06);box-sizing:border-box}.swal2-popup .swal2-file.swal2-inputerror,.swal2-popup .swal2-input.swal2-inputerror,.swal2-popup .swal2-textarea.swal2-inputerror{border-color:#f27474!important;box-shadow:0 0 2px #f27474!important}.swal2-popup .swal2-file:focus,.swal2-popup .swal2-input:focus,.swal2-popup .swal2-textarea:focus{border:1px solid #b4dbed;outline:0;box-shadow:0 0 3px #c4e6f5}.swal2-popup .swal2-file::-webkit-input-placeholder,.swal2-popup .swal2-input::-webkit-input-placeholder,.swal2-popup .swal2-textarea::-webkit-input-placeholder{color:#ccc}.swal2-popup .swal2-file:-ms-input-placeholder,.swal2-popup .swal2-input:-ms-input-placeholder,.swal2-popup .swal2-textarea:-ms-input-placeholder{color:#ccc}.swal2-popup .swal2-file::-ms-input-placeholder,.swal2-popup .swal2-input::-ms-input-placeholder,.swal2-popup .swal2-textarea::-ms-input-placeholder{color:#ccc}.swal2-popup .swal2-file::placeholder,.swal2-popup .swal2-input::placeholder,.swal2-popup .swal2-textarea::placeholder{color:#ccc}.swal2-popup .swal2-range{margin:1em auto;background:inherit}.swal2-popup .swal2-range input{width:80%}.swal2-popup .swal2-range output{width:20%;font-weight:600;text-align:center}.swal2-popup .swal2-range input,.swal2-popup .swal2-range output{height:2.625em;padding:0;font-size:1.125em;line-height:2.625em}.swal2-popup .swal2-input{height:2.625em;padding:0 .75em}.swal2-popup .swal2-input[type=number]{max-width:10em}.swal2-popup .swal2-file{background:inherit;font-size:1.125em}.swal2-popup .swal2-textarea{height:6.75em;padding:.75em}.swal2-popup .swal2-select{min-width:50%;max-width:100%;padding:.375em .625em;background:inherit;color:#545454;font-size:1.125em}.swal2-popup .swal2-checkbox,.swal2-popup .swal2-radio{align-items:center;justify-content:center;background:inherit}.swal2-popup .swal2-checkbox label,.swal2-popup .swal2-radio label{margin:0 .6em;font-size:1.125em}.swal2-popup .swal2-checkbox input,.swal2-popup .swal2-radio input{margin:0 .4em}.swal2-popup .swal2-validation-message{display:none;align-items:center;justify-content:center;padding:.625em;background:#f0f0f0;color:#666;font-size:1em;font-weight:300;overflow:hidden}.swal2-popup .swal2-validation-message::before{display:inline-block;width:1.5em;min-width:1.5em;height:1.5em;margin:0 .625em;border-radius:50%;background-color:#f27474;color:#fff;font-weight:600;line-height:1.5em;text-align:center;content:'!';zoom:normal}@supports (-ms-accelerator:true){.swal2-range input{width:100%!important}.swal2-range output{display:none}}@media all and (-ms-high-contrast:none),(-ms-high-contrast:active){.swal2-range input{width:100%!important}.swal2-range output{display:none}}@-moz-document url-prefix(){.swal2-close:focus{outline:2px solid rgba(50,100,150,.4)}}.swal2-icon{position:relative;justify-content:center;width:5em;height:5em;margin:1.25em auto 1.875em;border:.25em solid transparent;border-radius:50%;line-height:5em;cursor:default;box-sizing:content-box;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;zoom:normal}.swal2-icon::before{display:flex;align-items:center;height:92%;font-size:3.75em}.swal2-icon.swal2-error{border-color:#f27474}.swal2-icon.swal2-error .swal2-x-mark{position:relative;flex-grow:1}.swal2-icon.swal2-error [class^=swal2-x-mark-line]{display:block;position:absolute;top:2.3125em;width:2.9375em;height:.3125em;border-radius:.125em;background-color:#f27474}.swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=left]{left:1.0625em;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=right]{right:1em;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.swal2-icon.swal2-warning{border-color:#facea8;color:#f8bb86}.swal2-icon.swal2-warning::before{content:'!'}.swal2-icon.swal2-info{border-color:#9de0f6;color:#3fc3ee}.swal2-icon.swal2-info::before{content:'i'}.swal2-icon.swal2-question{border-color:#c9dae1;color:#87adbd}.swal2-icon.swal2-question::before{content:'?'}.swal2-icon.swal2-question.swal2-arabic-question-mark::before{content:'؟'}.swal2-icon.swal2-success{border-color:#a5dc86}.swal2-icon.swal2-success [class^=swal2-success-circular-line]{position:absolute;width:3.75em;height:7.5em;-webkit-transform:rotate(45deg);transform:rotate(45deg);border-radius:50%}.swal2-icon.swal2-success [class^=swal2-success-circular-line][class$=left]{top:-.4375em;left:-2.0635em;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:3.75em 3.75em;transform-origin:3.75em 3.75em;border-radius:7.5em 0 0 7.5em}.swal2-icon.swal2-success [class^=swal2-success-circular-line][class$=right]{top:-.6875em;left:1.875em;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:0 3.75em;transform-origin:0 3.75em;border-radius:0 7.5em 7.5em 0}.swal2-icon.swal2-success .swal2-success-ring{position:absolute;top:-.25em;left:-.25em;width:100%;height:100%;border:.25em solid rgba(165,220,134,.3);border-radius:50%;z-index:2;box-sizing:content-box}.swal2-icon.swal2-success .swal2-success-fix{position:absolute;top:.5em;left:1.625em;width:.4375em;height:5.625em;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);z-index:1}.swal2-icon.swal2-success [class^=swal2-success-line]{display:block;position:absolute;height:.3125em;border-radius:.125em;background-color:#a5dc86;z-index:2}.swal2-icon.swal2-success [class^=swal2-success-line][class$=tip]{top:2.875em;left:.875em;width:1.5625em;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.swal2-icon.swal2-success [class^=swal2-success-line][class$=long]{top:2.375em;right:.5em;width:2.9375em;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.swal2-progress-steps{align-items:center;margin:0 0 1.25em;padding:0;background:inherit;font-weight:600}.swal2-progress-steps li{display:inline-block;position:relative}.swal2-progress-steps .swal2-progress-step{width:2em;height:2em;border-radius:2em;background:#3085d6;color:#fff;line-height:2em;text-align:center;z-index:20}.swal2-progress-steps .swal2-progress-step.swal2-active-progress-step{background:#3085d6}.swal2-progress-steps .swal2-progress-step.swal2-active-progress-step~.swal2-progress-step{background:#add8e6;color:#fff}.swal2-progress-steps .swal2-progress-step.swal2-active-progress-step~.swal2-progress-step-line{background:#add8e6}.swal2-progress-steps .swal2-progress-step-line{width:2.5em;height:.4em;margin:0 -1px;background:#3085d6;z-index:10}[class^=swal2]{-webkit-tap-highlight-color:transparent}.swal2-show{-webkit-animation:swal2-show .3s;animation:swal2-show .3s}.swal2-show.swal2-noanimation{-webkit-animation:none;animation:none}.swal2-hide{-webkit-animation:swal2-hide .15s forwards;animation:swal2-hide .15s forwards}.swal2-hide.swal2-noanimation{-webkit-animation:none;animation:none}.swal2-rtl .swal2-close{right:auto;left:0}.swal2-animate-success-icon .swal2-success-line-tip{-webkit-animation:swal2-animate-success-line-tip .75s;animation:swal2-animate-success-line-tip .75s}.swal2-animate-success-icon .swal2-success-line-long{-webkit-animation:swal2-animate-success-line-long .75s;animation:swal2-animate-success-line-long .75s}.swal2-animate-success-icon .swal2-success-circular-line-right{-webkit-animation:swal2-rotate-success-circular-line 4.25s ease-in;animation:swal2-rotate-success-circular-line 4.25s ease-in}.swal2-animate-error-icon{-webkit-animation:swal2-animate-error-icon .5s;animation:swal2-animate-error-icon .5s}.swal2-animate-error-icon .swal2-x-mark{-webkit-animation:swal2-animate-error-x-mark .5s;animation:swal2-animate-error-x-mark .5s}@-webkit-keyframes swal2-rotate-loading{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes swal2-rotate-loading{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@media print{body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown){overflow-y:scroll!important}body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown)>[aria-hidden=true]{display:none}body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) .swal2-container{position:initial!important}}");