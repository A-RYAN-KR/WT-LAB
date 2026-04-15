import React, { useState } from 'react';
import './App.css';

const SubjectInput = ({ label, name, onChange }) => (
  <div className="subject-group">
    <div className="label-text">{label}</div>
    <div className="input-row">
      <input type="number" placeholder="MSE (30)" name={`${name}_mse`} onChange={onChange} className="input-field" />
      <input type="number" placeholder="ESE (70)" name={`${name}_ese`} onChange={onChange} className="input-field" />
    </div>
  </div>
);

export default function ResultSystem() {
  const [formData, setFormData] = useState({});
  const [result, setResult] = useState(null);

  const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

  const submit = async () => {
    // Ensure this matches your XAMPP folder name
    const res = await fetch('http://localhost/vit_backend/save_result.php', {
      method: 'POST',
      body: JSON.stringify(formData)
    });
    const data = await res.json();
    setResult(data.percentage);
  };

  return (
    <div className="main-container">
      <div className="result-card">
        <h2 className="header">VIT Semester Result Portal</h2>
        
        <div className="student-info">
          <input name="name" placeholder="Student Name" onChange={handleChange} className="input-field" />
          <input name="prn" placeholder="PRN (e.g. 1221xxxx)" onChange={handleChange} className="input-field" />
        </div>

        <SubjectInput label="Data Structures (DSA)" name="dsa" onChange={handleChange} />
        <SubjectInput label="Operating Systems (OS)" name="os" onChange={handleChange} />
        <SubjectInput label="DBMS" name="dbms" onChange={handleChange} />
        <SubjectInput label="Theory of Comp. (TOC)" name="toc" onChange={handleChange} />

        <button onClick={submit} className="submit-btn">
          Generate & Save Result
        </button>

        {result && (
          <div className="result-alert">
            <p className="alert-title">Calculation Complete!</p>
            <p>Final Aggregate Score: <span className="percentage-text">{result.toFixed(2)}%</span></p>
          </div>
        )}
      </div>
    </div>
  );
}