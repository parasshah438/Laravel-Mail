<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        if (navigator.permissions) {
            // Check geolocation permission state
            navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                if (permissionStatus.state === 'granted') {
                    // Location access granted
                    getLocationAndSendToServer();
                } else if (permissionStatus.state === 'prompt') {
                    // Permission is not yet granted or denied, request location
                    getLocationAndSendToServer();
                } else if (permissionStatus.state === 'denied') {
                    // Location access denied
                    alert('You have denied location access. Please allow location services in your browser settings.');
                }

                // Listen for changes in permission status
                permissionStatus.onchange = function() {
                    console.log('Permission state changed to', this.state);
                };
            });
        } else {
            // Fallback for browsers that do not support navigator.permissions
            getLocationAndSendToServer();
        }
    
        function getLocationAndSendToServer() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const currentLatitude = position.coords.latitude;
                    const currentLongitude = position.coords.longitude;

                    alert('Latitude: ' + currentLatitude + ' Longitude: ' + currentLongitude);

                }, function(error) {
                    alert('Error getting location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    });
</script>

