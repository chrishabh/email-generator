<!-- resources/views/components/support-widget.blade.php -->
<style>
      #supportIcon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 64px;
    height: 64px;
    background: linear-gradient(145deg, #007bff, #0056d2);
    color: white;
    font-size: 28px;
    font-weight: bold;
    border-radius: 50%;
    box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4), inset 0 0 8px rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
    animation: floatUpDown 2.5s ease-in-out infinite;
    backdrop-filter: blur(6px);
  }

  #supportIcon:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 24px rgba(0, 123, 255, 0.5), inset 0 0 12px rgba(255, 255, 255, 0.3);
  }

  @keyframes floatUpDown {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-5px);
    }
  }

  #supportIcon::after {
    content: "💬";
    font-size: 24px;
  }

  #supportForm {
    display: none;
    position: fixed;
    bottom: 100px;
    right: 20px;
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    width: 320px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    font-family: 'Segoe UI', Roboto, sans-serif;
    z-index: 1000;
    animation: slideIn 0.3s ease;
  }

  #supportForm h4 {
    margin-top: 0;
    font-size: 20px;
    font-weight: 600;
    color: #333;
  }

  #supportForm input,
  #supportForm textarea {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    margin-bottom: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    font-size: 14px;
    transition: border 0.2s;
  }

  #supportForm input:focus,
  #supportForm textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
  }

  #supportForm button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    border: none;
    color: white;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  #supportForm button:hover {
    background-color: #0056b3;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeInOut {
  0% { opacity: 0; transform: translateY(-20px); }
  10% { opacity: 1; transform: translateY(0); }
  90% { opacity: 1; transform: translateY(0); }
  100% { opacity: 0; transform: translateY(-20px); }
}

</style>

<div id="supportIcon" title="Contact Support"></div>

<div id="supportForm">
  <h4>💬 Contact Support</h4>
  <input type="email" id="supportEmail" placeholder="Your Email" required>
  <textarea id="supportMessage" rows="5" placeholder="How can we help you?"></textarea>
  <button onclick="submitSupport()">Send Request</button>
</div>

<div id="supportToast" style="
  position: fixed;
  top: 30px;
  right: 30px;
  background-color: #28a745;
  color: white;
  padding: 16px 24px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  font-family: 'Segoe UI', sans-serif;
  font-size: 15px;
  font-weight: 500;
  z-index: 2000;
  display: none;
  animation: fadeInOut 4s ease forwards;
"></div>


<script>
    document.getElementById('supportIcon').addEventListener('click', function () {
        const form = document.getElementById('supportForm');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    });

    function showToast(message, isSuccess = true) {
    const toast = document.getElementById('supportToast');
    toast.innerText = message;
    toast.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545';
    toast.style.display = 'block';
    toast.style.animation = 'none';
    void toast.offsetWidth; // trigger reflow to restart animation
    toast.style.animation = 'fadeInOut 4s ease forwards';
}

    function submitSupport() {
        const email = document.getElementById('supportEmail').value;
        const message = document.getElementById('supportMessage').value;

        fetch("{{ route('supportSend') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email, message })
        })
        .then(response => {
            if (response.ok) {
                showToast("✅ Support request sent!");
                document.getElementById('supportForm').style.display = 'none';
                document.getElementById('supportEmail').value = '';
                document.getElementById('supportMessage').value = '';
            } else {
                showToast("❌ Failed to send support request.", false);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
</script>
