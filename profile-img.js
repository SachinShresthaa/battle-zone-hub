$(document).ready(function() {
    // Trigger file input when clicking on profile image container
    $('.profile-image-container').click(function() {
        $('#image-upload').click();
    });

    // Handle file selection
    $('#image-upload').change(function() {
        const file = this.files[0];
        if (file) {
            // Check file type
            if (!file.type.match('image.*')) {
                alert('Please select an image file');
                return;
            }
            
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size should be less than 5MB');
                return;
            }

            // Create FormData and append file
            const formData = new FormData();
            formData.append('profile_image', file);

            // Show loading state
            $('#profile-image').css('opacity', '0.5');

            // Upload image using AJAX
            $.ajax({
                url: 'upload_profile_image.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            // Update image preview
                            $('#profile-image').attr('src', result.image_url);
                            // Optional: Show success message
                            alert('Profile picture updated successfully!');
                        } else {
                            alert(result.message || 'Failed to upload image');
                        }
                    } catch (e) {
                        alert('An error occurred while uploading the image');
                    }
                },
                error: function() {
                    alert('An error occurred while uploading the image');
                },
                complete: function() {
                    // Reset loading state
                    $('#profile-image').css('opacity', '1');
                }
            });
        }
    });
});