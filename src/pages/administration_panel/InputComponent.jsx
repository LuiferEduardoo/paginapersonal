import React, { useState, useEffect } from "react";
import { htmlToText } from 'html-to-text';

const InputComponent = ({ title, TypeInput='input', id, type='text', setElement, required=false, defaulValue=null, placeholder=''}) =>{
    const currentDate = new Date().toISOString().split("T")[0];
    return(
        <div className="flex flex-col">
            <label htmlFor={id} className="text-sm font-medium text-gray-700 flex items-start">{title}:</label>
            <TypeInput
                type={type}
                id={id}
                onChange={event => setElement(event.target.value)}
                defaultValue={TypeInput === 'textarea' ? htmlToText(defaulValue) : defaulValue}
                placeholder={placeholder}
                {...(required && { required: true })}
                {...(type === 'date' && { max: currentDate })}
                className={`border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 ${TypeInput === 'textarea' ? 'h-80' : ''}`}
            />
        </div>
    )
}

export { InputComponent };