<!DOCTYPE html>
@if (!Route::is(['layout-mini', 'layout-hidden', 'layout-hover-view', 'layout-full-width', 'layout-rtl', 'layout-dark']))
    <html lang="en">
@endif

@if (Route::is(['layout-dark']))
    <html lang="en" data-bs-theme="dark">
@endif

@if (Route::is(['layout-hidden']))
    <html lang="en" data-layout="hidden">
@endif

@if (Route::is(['layout-hover-view']))
    <html lang="en" data-layout="hoverview">
@endif

@if (Route::is(['layout-mini']))
    <html lang="en" data-layout="mini">
@endif

@if (Route::is(['layout-rtl']))
    <html lang="en" dir="rtl">
@endif

@if (Route::is(['layout-full-width']))
    <html lang="en" data-layout="full-width">
@endif

@include('layout.partials.title-meta')

@if (!Route::is(['layout-mini']))

    <body>
@endif

@if (Route::is(['layout-mini']))

    <body class="mini-sidebar">
@endif

<!-- Start Main Wrapper -->
@if (
    !Route::is([
        'login-basic',
        'login-illustration',
        'login-cover',
        'login',
        'register-basic',
        'register-illustration',
        'register-cover',
        'forgot-password-basic',
        'forgot-password-illustration',
        'forgot-password-cover',
        'reset-password-basic',
        'reset-password-illustration',
        'reset-password-cover',
        'email-verification-basic',
        'email-verification-illustration',
        'email-verification-cover',
        'success-basic',
        'success-illustration',
        'success-cover',
        'two-step-verification-basic',
        'two-step-verification-illustration',
        'two-step-verification-cover',
        'lock-screen',
        'error-404',
        'error-500',
        'coming-soon',
        'under-maintenance',
    ]))
    <div class="main-wrapper">
@endif

@if (Route::is(['login']))
    <div class="main-wrapper auth-bg auth-bg-custom position-relative overflow-hidden">
@endif

@if (Route::is(['coming-soon', 'under-maintenance']))
    <div class="main-wrapper auth-bg">
@endif

@if (Route::is([
        'login-basic',
        'login-illustration',
        'login-cover',
        'register-basic',
        'register-illustration',
        'register-cover',
        'forgot-password-basic',
        'forgot-password-illustration',
        'forgot-password-cover',
        'reset-password-basic',
        'reset-password-illustration',
        'reset-password-cover',
        'email-verification-basic',
        'email-verification-illustration',
        'email-verification-cover',
        'success-basic',
        'success-illustration',
        'success-cover',
        'two-step-verification-basic',
        'two-step-verification-illustration',
        'two-step-verification-cover',
        'lock-screen',
        'error-404',
        'error-500',
    ]))
    <div class="main-wrapper auth-bg position-relative overflow-hidden">
@endif

@if (
    !Route::is([
        'login-basic',
        'login-illustration',
        'login-cover',
        'login',
        'register-basic',
        'register-illustration',
        'register-cover',
        'forgot-password-basic',
        'forgot-password-illustration',
        'forgot-password-cover',
        'reset-password-basic',
        'reset-password-illustration',
        'reset-password-cover',
        'email-verification-basic',
        'email-verification-illustration',
        'email-verification-cover',
        'success-basic',
        'success-illustration',
        'success-cover',
        'two-step-verification-basic',
        'two-step-verification-illustration',
        'two-step-verification-cover',
        'lock-screen',
        'error-404',
        'error-500',
        'coming-soon',
        'under-maintenance',
    ]))
    @include('layout.partials.header')

    @include('layout.partials.sidebar')
@endif

@yield('content')

@component('components.modal-popup')
@endcomponent

</div>
<!-- End Main Wrapper -->

@include('layout.partials.footer-scripts')
@livewireScripts
<script>
    window.addEventListener('show-toast', (event) => {
        alert(event.detail.message);
    });
</script>
<script>
    document.addEventListener('livewire:init', () => {

        Livewire.on('show-toast', (data) => {

            let toast = document.createElement('div');
            toast.innerText = data.message;

            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.background = '#28a745';
            toast.style.color = '#fff';
            toast.style.padding = '12px 20px';
            toast.style.borderRadius = '6px';
            toast.style.zIndex = '9999';
            toast.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 2500);
        });

    });
</script>
</body>

</html>
