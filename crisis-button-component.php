<?php
/**
 * 988 Crisis Button Component
 * Include this on all Ohio-related pages
 */
?>

<div class="crisis-helpline-bar">
    <div class="crisis-container">
        <div class="crisis-content">
            <span class="crisis-icon">🆘</span>
            <div class="crisis-text">
                <strong>Crisis Support Available 24/7</strong>
                <p>If you or someone you know is in crisis, help is available immediately</p>
            </div>
        </div>
        <div class="crisis-actions">
            <a href="tel:988" class="btn-crisis-call">
                <span class="phone-icon">📞</span>
                Call 988
            </a>
            <a href="sms:741741&body=4HOPE" class="btn-crisis-text">
                <span class="text-icon">💬</span>
                Text 4HOPE to 741741
            </a>
            <button class="btn-crisis-info" onclick="toggleCrisisInfo()">
                <span>ℹ️</span>
                More Info
            </button>
        </div>
    </div>
    
    <!-- Expandable Info Section -->
    <div id="crisisInfo" class="crisis-info-section" style="display: none;">
        <div class="crisis-info-content">
            <h3>988 Suicide & Crisis Lifeline</h3>
            <p><strong>Call 988</strong> - Free, confidential support 24/7/365</p>
            <ul>
                <li>Talk to a trained crisis counselor</li>
                <li>Available in English and Spanish</li>
                <li>TTY: Use your preferred relay service or dial 711 then 988</li>
                <li>Completely confidential and free</li>
            </ul>
            
            <h3>Crisis Text Line - Text "4HOPE" to 741741</h3>
            <p>If you prefer to text:</p>
            <ul>
                <li>Text <strong>4HOPE</strong> to <strong>741741</strong></li>
                <li>Connect with a crisis counselor via text</li>
                <li>Available 24/7</li>
                <li>Free and confidential</li>
            </ul>
            
            <div class="crisis-when-to-use">
                <h4>When to Use These Services:</h4>
                <ul>
                    <li>Feeling overwhelmed or in emotional distress</li>
                    <li>Experiencing suicidal thoughts</li>
                    <li>Substance use crisis or relapse</li>
                    <li>Need someone to talk to right now</li>
                    <li>Supporting someone else in crisis</li>
                </ul>
            </div>
            
            <p class="crisis-disclaimer">
                <strong>Note:</strong> 988 is specifically for mental health and substance use crises.
                For immediate medical emergencies, call 911.
            </p>
        </div>
    </div>
</div>

<script>
function toggleCrisisInfo() {
    const infoSection = document.getElementById('crisisInfo');
    if (infoSection.style.display === 'none') {
        infoSection.style.display = 'block';
    } else {
        infoSection.style.display = 'none';
    }
}
</script>

<style>
/* Crisis Helpline Bar */
.crisis-helpline-bar {
    background: linear-gradient(135deg, #B11226 0%, #8B0F1F 100%);
    color: white;
    padding: 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.crisis-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}
.crisis-content {
    display: flex;
    align-items: center;
    gap: 15px;
}
.crisis-icon {
    font-size: 36px;
}
.crisis-text strong {
    display: block;
    font-size: 18px;
    margin-bottom: 4px;
}
.crisis-text p {
    font-size: 14px;
    margin: 0;
    opacity: 0.9;
}
.crisis-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.btn-crisis-call,
.btn-crisis-text,
.btn-crisis-info {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 16px;
}
.btn-crisis-call {
    background: white;
    color: #B11226;
}
.btn-crisis-call:hover {
    background: #f0f0f0;
    transform: scale(1.05);
}
.btn-crisis-text {
    background: #2E7D32;
    color: white;
}
.btn-crisis-text:hover {
    background: #246a28;
    transform: scale(1.05);
}
.btn-crisis-info {
    background: transparent;
    color: white;
    border: 2px solid white;
}
.btn-crisis-info:hover {
    background: rgba(255,255,255,0.2);
}

/* Expandable Info Section */
.crisis-info-section {
    max-width: 1400px;
    margin: 20px auto 0;
    background: white;
    color: #1F2933;
    padding: 30px;
    border-radius: 10px;
}
.crisis-info-content h3 {
    color: #B11226;
    font-size: 22px;
    margin-top: 20px;
    margin-bottom: 10px;
}
.crisis-info-content h3:first-child {
    margin-top: 0;
}
.crisis-info-content h4 {
    color: #2E7D32;
    font-size: 18px;
    margin-top: 15px;
    margin-bottom: 10px;
}
.crisis-info-content ul {
    margin-left: 20px;
    line-height: 1.8;
}
.crisis-info-content li {
    margin: 8px 0;
}
.crisis-when-to-use {
    background: #f9f9f9;
    padding: 20px;
    border-left: 4px solid #2E7D32;
    border-radius: 5px;
    margin: 20px 0;
}
.crisis-disclaimer {
    background: #fff3cd;
    border-left: 4px solid #C9A227;
    padding: 15px;
    margin-top: 20px;
    border-radius: 5px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .crisis-container {
        flex-direction: column;
        text-align: center;
    }
    .crisis-content {
        flex-direction: column;
    }
    .crisis-actions {
        justify-content: center;
        width: 100%;
    }
    .btn-crisis-call,
    .btn-crisis-text,
    .btn-crisis-info {
        flex: 1;
        min-width: 150px;
        justify-content: center;
    }
}
</style>
