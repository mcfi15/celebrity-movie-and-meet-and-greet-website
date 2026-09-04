/**
 * Copy-to-Clipboard utility with visual feedback and toast notifications.
 *
 * Usage:
 *   <button type="button" class="copy-wallet-btn" data-copy-address="SOME_CRYPTO_ADDRESS">
 *       <i class="fas fa-copy"></i> Copy Address
 *   </button>
 *
 * On click the button temporarily shows "Copied!" with a checkmark, and a small
 * toast appears. A graceful fallback (execCommand) is used when the Clipboard
 * API is unavailable, e.g. non-secure HTTP contexts.
 */
(function (global) {
    'use strict';

    function showToast(message, isError) {
        var toast = document.createElement('div');
        toast.className = 'copy-toast' + (isError ? ' copy-toast-error' : '');
        toast.setAttribute('role', 'status');
        toast.textContent = message;
        document.body.appendChild(toast);

        // Force reflow so the transition fires.
        void toast.offsetWidth;
        toast.classList.add('copy-toast-show');

        setTimeout(function () {
            toast.classList.remove('copy-toast-show');
            setTimeout(function () {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 2600);
    }

    function legacyCopy(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'fixed';
        textarea.style.top = '0';
        textarea.style.left = '0';
        textarea.style.opacity = '0';
        textarea.style.pointerEvents = 'none';
        document.body.appendChild(textarea);
        textarea.select();
        textarea.setSelectionRange(0, textarea.value.length);

        var ok = false;
        try {
            ok = document.execCommand('copy');
        } catch (e) {
            ok = false;
        }
        document.body.removeChild(textarea);
        return ok;
    }

    function writeText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText && window.isSecureContext) {
            return navigator.clipboard.writeText(text)
                .catch(function () {
                    // Degrade gracefully if the secure-context call still fails.
                    return legacyCopy(text) ? global.Promise.resolve() : global.Promise.reject(new Error('copy failed'));
                });
        }

        return legacyCopy(text) ? global.Promise.resolve() : global.Promise.reject(new Error('copy failed'));
    }

    function bindButton(button) {
        if (button.getAttribute('data-copy-bound')) {
            return;
        }
        button.setAttribute('data-copy-bound', '1');

        button.addEventListener('click', function (e) {
            e.preventDefault();
            var address = button.getAttribute('data-copy-address') || '';
            if (!address) {
                showToast('Nothing to copy.', true);
                return;
            }

            var originalHtml = button.innerHTML;
            var successLabel = button.getAttribute('data-copy-label') || '<i class="fas fa-check"></i> Copied!';

            writeText(address).then(function () {
                showToast(button.getAttribute('data-copy-message') || 'Wallet address copied to clipboard.');
                button.innerHTML = successLabel;
                button.classList.add('copy-success');
                setTimeout(function () {
                    button.innerHTML = originalHtml;
                    button.classList.remove('copy-success');
                }, 2500);
            }).catch(function () {
                showToast('Could not copy automatically. Please copy the address manually.', true);
            });
        });
    }

    function init(root) {
        root = root || global.document;
        if (!root.querySelectorAll) {
            return;
        }
        Array.prototype.forEach.call(root.querySelectorAll('[data-copy-address]'), bindButton);
    }

    global.CopyClipboard = {
        init: init,
        writeText: writeText,
        showToast: showToast
    };

    if (global.document) {
        if (global.document.readyState === 'loading') {
            global.document.addEventListener('DOMContentLoaded', function () { init(); });
        } else {
            init();
        }
    }
})(window);