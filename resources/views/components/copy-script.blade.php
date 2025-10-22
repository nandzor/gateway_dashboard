@props([
    'showNotification' => true,
    'notificationDuration' => 3000
])

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const value = element.value || element.textContent;

    if (!value) {
        @if($showNotification)
            showCopyNotification('No value to copy!', 'error');
        @else
            console.warn('No value to copy!');
        @endif
        return;
    }

    navigator.clipboard.writeText(value).then(function() {
        @if($showNotification)
            showCopyNotification('Copied to clipboard!', 'success');
        @else
            console.log('Copied to clipboard!');
        @endif
    }).catch(function(err) {
        // Fallback for older browsers
        element.select();
        document.execCommand('copy');
        @if($showNotification)
            showCopyNotification('Copied to clipboard!', 'success');
        @else
            console.log('Copied to clipboard!');
        @endif
    });
}

@if($showNotification)
function showCopyNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.copy-notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'copy-notification fixed top-4 right-4 z-50 px-4 py-2 rounded-md text-white text-sm font-medium transition-all duration-300 ' +
        (type === 'success' ? 'bg-green-500' : 'bg-red-500');
    notification.textContent = message;

    // Add to page
    document.body.appendChild(notification);

    // Remove after specified duration
    setTimeout(() => {
        notification.remove();
    }, {{ $notificationDuration }});
}
@endif
</script>
