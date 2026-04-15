import React, { useState } from 'react';
import './App.css';

export default function ElectricityBill() {
  const [formData, setFormData] = useState({ name: '', units: '' });
  const [result, setResult] = useState(null);

  const calculate = async () => {
    const res = await fetch('http://localhost/calculate_bill.php', {
      method: 'POST',
      body: JSON.stringify(formData)
    });
    const data = await res.json();
    setResult(data.amount);
  };

  return (
    <div className="main-container">
      <div className="card">
        <h2 className="header">Electricity Bill Calculator</h2>
        
        <div className="input-group">
          <label>Customer Name</label>
          <input 
            type="text" 
            placeholder="Enter name" 
            onChange={(e) => setFormData({...formData, name: e.target.value})} 
          />
        </div>

        <div className="input-group">
          <label>Units Consumed</label>
          <input 
            type="number" 
            placeholder="Enter units" 
            onChange={(e) => setFormData({...formData, units: e.target.value})} 
          />
        </div>

        <button className="calc-btn" onClick={calculate}>Generate Bill</button>

        {result !== null && (
          <div className="bill-display">
            <p>Total Units: <strong>{formData.units}</strong></p>
            <h3>Total Amount: ₹{result.toFixed(2)}</h3>
            <div className="slab-info">Calculated based on VIT standard slabs</div>
          </div>
        )}
      </div>
    </div>
  );
}