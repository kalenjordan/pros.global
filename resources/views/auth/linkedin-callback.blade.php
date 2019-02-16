<html>
<body>
    <script type="text/javascript">
        window.location='http://localhost:3000/auth?api_token={{ Auth::user()->api_token }}';
        {{--window.opener.Events.$emit('user-authenticated', JSON.stringify({!! Auth::user() ? json_encode(Auth::user()->toArrayForCookie()) : null  !!}));--}}
        {{--window.close();--}}
    </script>
</body>
</html>