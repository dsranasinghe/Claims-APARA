<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Default Report - Sri Lanka Export Credit Insurance Corporation</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f7f9;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 20px;
        }

        h1 {
            color: #2c5aa0;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .address {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .contact {
            font-size: 14px;
            color: #555;
        }

        .instructions {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #2c5aa0;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .instructions p {
            margin-bottom: 10px;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c5aa0;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        button {
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        #previewBtn {
            background-color: #2c5aa0;
            color: white;
        }

        #previewBtn:hover {
            background-color: #1e3d73;
        }

        #downloadBtn {
            background-color: #28a745;
            color: white;
        }

        #downloadBtn:hover {
            background-color: #218838;
        }

        #resetBtn {
            background-color: #dc3545;
            color: white;
        }

        #resetBtn:hover {
            background-color: #c82333;
        }

        .preview-container {
            display: none;
            margin-top: 40px;
            border: 1px solid #ddd;
            padding: 30px;
            background: white;
        }

        .preview-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .preview-content .form-group {
            margin-bottom: 15px;
        }

        .signature-area {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .signature-line {
            width: 300px;
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            padding-top: 40px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close-modal {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .actions {
                flex-direction: column;
                gap: 10px;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>SRI LANKA EXPORT CREDIT INSURANCE CORPORATION</h1>
            <div class="address">Local (N, K)D8-E08 Tower</div>
            <div class="address">No. 612, Norman Morrides, Columbus 02</div>
            <div class="contact">Tel: 0113-265751-963 Fax: 0110-289702</div>
        </header>

        <h2>REPORT OF DEFAULT UNDER THE WHOLE-TURNOVER BANK GUARANTEE COVERING<br>THE COST OF PASSAGE ADVANCES TO SRI LANKA'S GOING ABROAD FOR INDEPENDENT</h2>

        <div class="instructions">
            <p>1. This report has to be sent to the Corporation in duplicate.</p>
            <p>2. This report has to be submitted to the Corporation within one month from the date date (date of default) in terms of condition (No. 3) of the Whole-Turnover Bank Guarantee.</p>
        </div>

        <p>Dear Sirs,<br>
            In terms of Whole-Turnover Bank Guarantee covering Cost of Passage Advances bearing No. CPA/W17, ______ issued to us we hereby submit the Report of Default, the details of which are given below:</p>


        <div class="form-section">
            <div class="form-group">
                <label for="bankName">1. Name of the Bank and address (Branch):</label>
                <input type="text" id="bankName" placeholder="Enter bank name and address">
            </div>

            <div class="form-group">
                <label for="debtorInfo">2. Name & address of the Debtor:</label>
                <input type="text" id="debtorInfo" placeholder="Enter debtor name and address">
            </div>

            <div class="form-group">
                <label for="repayment">3. Total repayment made:</label>
                <input type="text" id="repayment" placeholder="Enter total repayment amount">
            </div>

            <div class="form-group">
                <label for="outstanding">4. Amount outstanding as at date:</label>
                <input type="text" id="outstanding" placeholder="Enter outstanding amount">
            </div>

            <div class="form-group">
                <label for="reasons">5. Reasons for default:</label>
                <textarea id="reasons" placeholder="Provide reasons for default"></textarea>
            </div>

            <div class="form-group">
                <label for="demandMade">6. Whether demand has been made:</label>
                <input type="text" id="demandMade" placeholder="Yes/No with details">
            </div>

            <div class="form-group">
                <label for="demandLetter">7. (a) If not reasoned by the letter of Demand:</label>
                <textarea id="demandLetter" placeholder="Provide details"></textarea>
            </div>

            <div class="form-group">
                <label for="recoverySteps">8. Steps taken for Recovery of the debt and further steps proposed to be taken by the Bank:</label>
                <textarea id="recoverySteps" placeholder="Describe steps taken and planned"></textarea>
            </div>

            <div class="form-group">
                <label for="otherInfo">9. Any other information relevant:</label>
                <textarea id="otherInfo" placeholder="Provide any additional relevant information"></textarea>
            </div>
        </div>

        <div class="actions">
            <button id="previewBtn">Preview Report</button>
            <button id="downloadBtn">Download as PDF</button>
            <button id="resetBtn">Reset Form</button>
        </div>

        <div id="previewModal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <div id="reportPreview" class="preview-container">
                    <div class="preview-header">
                        <h1>SRI LANKA EXPORT CREDIT INSURANCE CORPORATION</h1>
                        <div class="address">Local (N, K)D8-E08 Tower</div>
                        <div class="address">No. 612, Norman Morrides, Columbus 02</div>
                        <div class="contact">Tel: 0113-265751-963 Fax: 0110-289702</div>
                    </div>

                    <h2>REPORT OF DEFAULT UNDER THE WHOLE-TURNOVER BANK GUARANTEE COVERING<br>THE COST OF PASSAGE ADVANCES TO SRI LANKA'S GOING ABROAD FOR INDEPENDENT</h2>

                    <div class="instructions">
                        <p>1. This report has to be sent to the Corporation in duplicate.</p>
                        <p>2. This report has to be submitted to the Corporation within one month from the date date (date of default) in terms of condition (No. 6) of the Whole-Turnover Bank Guarantee.</p>
                    </div>

                    <p>Dear Sirs,<br>
                        In terms of Whole-Turnover Bank Guarantee covering Cost of Passage Advances bearing 
                        No. CPA/W17, ______ issued to us we hereby submit the Report of Default, the details of which are given below:</p>

                    <div class="preview-content">
                        <div class="form-group">
                            <label>1. Name of the Bank and address (Branch):</label>
                            <div id="preview-bankName"></div>
                        </div>

                        <div class="form-group">
                            <label>2. Name & address of the Debtor:</label>
                            <div id="preview-debtorInfo"></div>
                        </div>

                        <div class="form-group">
                            <label>3. Total repayment made:</label>
                            <div id="preview-repayment"></div>
                        </div>

                        <div class="form-group">
                            <label>4. Amount outstanding as at date:</label>
                            <div id="preview-outstanding"></div>
                        </div>

                        <div class="form-group">
                            <label>5. Reasons for default:</label>
                            <div id="preview-reasons"></div>
                        </div>

                        <div class="form-group">
                            <label>6. Whether demand has been made:</label>
                            <div id="preview-demandMade"></div>
                        </div>

                        <div class="form-group">
                            <label>7. (a) If not reasoned by the letter of Demand:</label>
                            <div id="preview-demandLetter"></div>
                        </div>

                        <div class="form-group">
                            <label>8. Steps taken for Recovery of the debt and further steps proposed to be taken by the Bank:</label>
                            <div id="preview-recoverySteps"></div>
                        </div>

                        <div class="form-group">
                            <label>9. Any other information relevant:</label>
                            <div id="preview-otherInfo"></div>
                        </div>
                    </div>

                    <div class="signature-area">
                        <div class="signature-line"></div>
                        <div>Signature</div>

                        <div style="margin-top: 30px;">
                            <div><strong>Designation:</strong> _________________________________</div>
                            <div style="margin-top: 15px;"><strong>Name & Address of the Bank Submitting the Report:</strong></div>
                            <div>_________________________________</div>
                            <div style="margin-top: 15px;"><strong>Date:</strong> _________________________</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewBtn = document.getElementById('previewBtn');
            const downloadBtn = document.getElementById('downloadBtn');
            const resetBtn = document.getElementById('resetBtn');
            const modal = document.getElementById('previewModal');
            const closeModal = document.querySelector('.close-modal');

            // Preview button event
            previewBtn.addEventListener('click', function() {
                // Update preview content
                document.getElementById('preview-bankName').textContent = document.getElementById('bankName').value || 'Not provided';
                document.getElementById('preview-debtorInfo').textContent = document.getElementById('debtorInfo').value || 'Not provided';
                document.getElementById('preview-repayment').textContent = document.getElementById('repayment').value || 'Not provided';
                document.getElementById('preview-outstanding').textContent = document.getElementById('outstanding').value || 'Not provided';
                document.getElementById('preview-reasons').textContent = document.getElementById('reasons').value || 'Not provided';
                document.getElementById('preview-demandMade').textContent = document.getElementById('demandMade').value || 'Not provided';
                document.getElementById('preview-demandLetter').textContent = document.getElementById('demandLetter').value || 'Not provided';
                document.getElementById('preview-recoverySteps').textContent = document.getElementById('recoverySteps').value || 'Not provided';
                document.getElementById('preview-otherInfo').textContent = document.getElementById('otherInfo').value || 'Not provided';

                // Show modal
                modal.style.display = 'flex';
            });

            // Download button event
            downloadBtn.addEventListener('click', function() {
                // First update preview content
                document.getElementById('preview-bankName').textContent = document.getElementById('bankName').value || 'Not provided';
                document.getElementById('preview-debtorInfo').textContent = document.getElementById('debtorInfo').value || 'Not provided';
                document.getElementById('preview-repayment').textContent = document.getElementById('repayment').value || 'Not provided';
                document.getElementById('preview-outstanding').textContent = document.getElementById('outstanding').value || 'Not provided';
                document.getElementById('preview-reasons').textContent = document.getElementById('reasons').value || 'Not provided';
                document.getElementById('preview-demandMade').textContent = document.getElementById('demandMade').value || 'Not provided';
                document.getElementById('preview-demandLetter').textContent = document.getElementById('demandLetter').value || 'Not provided';
                document.getElementById('preview-recoverySteps').textContent = document.getElementById('recoverySteps').value || 'Not provided';
                document.getElementById('preview-otherInfo').textContent = document.getElementById('otherInfo').value || 'Not provided';

                // Generate PDF
                const element = document.getElementById('reportPreview');
                const opt = {
                    margin: 10,
                    filename: 'Default_Report.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                html2pdf().set(opt).from(element).save();
            });

            // Reset button event
            resetBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                    document.getElementById('bankName').value = '';
                    document.getElementById('debtorInfo').value = '';
                    document.getElementById('repayment').value = '';
                    document.getElementById('outstanding').value = '';
                    document.getElementById('reasons').value = '';
                    document.getElementById('demandMade').value = '';
                    document.getElementById('demandLetter').value = '';
                    document.getElementById('recoverySteps').value = '';
                    document.getElementById('otherInfo').value = '';
                }
            });

            // Close modal event
            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Close modal if clicked outside
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Add this to your existing script in printOverduepdf.html
document.addEventListener('DOMContentLoaded', function() {
    // Get record ID from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const recordId = urlParams.get('id');
    
    if (recordId) {
        // Fetch record data and populate the form
        fetchRecordData(recordId);
    }
    
    // Listen for messages if using the iframe approach
    window.addEventListener('message', function(event) {
        if (event.data.type === 'generateOverduePDF') {
            populateForm(event.data.data);
            // Auto-generate the PDF
            generatePDF();
        }
    });
    
    function fetchRecordData(recordId) {
        // Fetch data from your server based on recordId
        fetch(`/api/get-record-data/${recordId}`)
            .then(response => response.json())
            .then(data => {
                populateForm(data);
            })
            .catch(error => {
                console.error('Error fetching record data:', error);
                alert('Failed to load record data.');
            });
    }
    
    function populateForm(data) {
        // Populate form fields with data
        document.getElementById('bankName').value = data.bankName || '';
        document.getElementById('debtorInfo').value = data.debtorInfo || '';
        document.getElementById('repayment').value = data.repayment || '';
        // Continue for all fields...
        
        // Auto-trigger download if needed
        // generatePDF();
    }
    
    function generatePDF() {
        // Your existing PDF generation code
        const element = document.getElementById('reportPreview');
        const opt = {
            margin: 10,
            filename: `Default_Report_${recordId || 'record'}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(element).save();
    }
});
    </script>
</body>

</html>