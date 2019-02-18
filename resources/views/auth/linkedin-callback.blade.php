<html>
<body>
    <script type="text/javascript">
        @if (env('AUTH_REDIRECT'))
            window.location='{{ env('AUTH_REDIRECT') }}?api_token={{ Auth::user()->api_token }}';
        @else
            window.opener.Events.$emit('user-authenticated', JSON.stringify({!! Auth::user() ? json_encode(Auth::user()->toArrayForCookie()) : null  !!}));
            window.close();
        @endif
    </script>
</body>
</html>