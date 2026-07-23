

function addFamilyMember() {
    const container = document.getElementById('family-container');
    const noText = document.getElementById('no-family-text');
    if(noText) noText.remove();

    const row = document.createElement('div');
    row.className = 'family-row';
    row.style = "display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr 0.8fr 50px; gap: 10px; background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 10px; align-items: end;";
    
    row.innerHTML = `
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;">Name</label>
            <input type="text" name="family[${familyIndex}][name]" required style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;">Mobile</label>
            <input type="text" name="family[${familyIndex}][mobile]" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;">WhatsApp</label>
            <input type="text" name="family[${familyIndex}][whatsapp_number]" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;">Relation</label>
            <input type="text" name="family[${familyIndex}][relation]" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;">Gender</label>
            <select name="family[${familyIndex}][gender]" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 5px;">Age</label>
            <input type="text" name="family[${familyIndex}][age]" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <button type="button" onclick="this.parentElement.remove()" style="background: #ef4444; color: white; border: none; height: 38px; width: 38px; border-radius: 8px; cursor: pointer;">&times;</button>
    `;
    
    container.appendChild(row);
    familyIndex++;
}

function fetchAddress() {
    let pincode = document.getElementById('pincode').value;
    if (pincode.length === 6) {
        fetch(`https://api.postalpincode.in/pincode/${pincode}`)
            .then(response => response.json())
            .then(data => {
                if (data[0].Status === "Success") {
                    let postOffice = data[0].PostOffice[0];
                    document.getElementById('area_name').value = postOffice.Name;
                    document.getElementById('district').value = postOffice.District;
                    document.getElementById('state').value = postOffice.State;
                    document.getElementById('circle').value = postOffice.Circle;
                    document.getElementById('country').value = postOffice.Country;
                }
            })
            .catch(error => console.log('Error fetching pincode data:', error));
    }
}