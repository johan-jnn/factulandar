@isset($text)
    <script>
        {
            function _show() {
                if ("Toastify" in window) {
                    Toastify({
                        text: `{!! $text !!}`,
                        className: "toast {{ $type }}",
                        selector: document.body
                    }).showToast();
                }
            }
            if (document?.body) _show();
            else window.addEventListener('load', () => _show());
        }
    </script>
@endisset
