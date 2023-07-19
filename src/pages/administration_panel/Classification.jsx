import React, { useState, useEffect } from "react";

const Classification =  ({ element, haveTechnology=false, activeSection, setActiveSection, technology=null, setTechnologies=null, technologies=null, categories, setCategories, subcategories, setSubcategories, tags, setTags }) =>{
    const [newCategory, setNewCategory] = useState("");
    const [newSubcategory, setNewSubcategory] = useState("");
    const [newTag, setNewTag] = useState("");
    const [newTechnology, setNewTechnology] = useState("");

    const handleSectionChange = (section) => {
        setActiveSection(section);
    };

    const handleAddClassification = (newClassification, setClassification, setNewClassification) => {
        if (newClassification.trim() !== "") {
            let classificationObject = newClassification;
            if(haveTechnology){
                const idTechnology = technology.find(
                    (technology) => technology.name === newClassification
                );
                if(idTechnology){
                    classificationObject = {id: idTechnology.id, name: newClassification}
                }
            }
            setClassification((prevClassifications) => [...prevClassifications, classificationObject]);
            setNewClassification("");
        }
    };

    const handleRemoveClassification = (classification, setClassification) => {
        setClassification((prevClassifications) => prevClassifications.filter((c) => c !== classification));
    };

    return (
        <div>
            <div>
                <button
                    className={`${activeSection === "category" ? "bg-blue-500" : "bg-gray-300"} px-4 py-2 rounded`}
                    onClick={() => handleSectionChange("category")}
                >
                Categoría
                </button>
                <button
                    className={`${activeSection === "subcategory" ? "bg-blue-500" : "bg-gray-300"} px-4 py-2 rounded`}
                    onClick={() => handleSectionChange("subcategory")}
                >
                Subcategoría
                </button>
                <button
                    className={`${activeSection === "tag" ? "bg-blue-500" : "bg-gray-300"} px-4 py-2 rounded`}
                    onClick={() => handleSectionChange("tag")}
                >
                Tag
                </button>
                {haveTechnology &&(
                    <button
                        className={`${activeSection === "technology" ? "bg-blue-500" : "bg-gray-300"} px-4 py-2 rounded`}
                        onClick={() => handleSectionChange("technology")}
                    >
                    Tecnologias
                    </button>
                )}
            </div>
            {activeSection === "category" && (
                <div className="p-4">
                    <h3 className="text-lg font-medium">Categoría</h3>
                    <div className="flex mt-2">
                        <input
                            type="text"
                            value={newCategory}
                            onChange={(e) => setNewCategory(e.target.value)}
                            className="flex-grow border border-gray-300 rounded-l-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <button 
                            onClick={() => handleAddClassification(newCategory, setCategories, setNewCategory)}
                            className="bg-indigo-500 text-white px-4 py-2 rounded-r-md hover:bg-indigo-600"
                        >
                            Agregar
                        </button>
                    </div>
                    <ul className="mt-4">
                        {categories.map((value) => (
                        <li key={value} className="flex items-center justify-between border border-gray-300 rounded-md px-3 py-2 mb-2">
                            <span>{value}</span>
                            <button
                                onClick={() => handleRemoveClassification(value, setCategories)}
                                className="text-red-500 hover:text-red-700 focus:outline-none"
                            >
                            X
                            </button>
                        </li>
                        ))}
                    </ul>
                </div>
            )}
            {activeSection === "subcategory" && (
                <div className="p-4">
                    <h3 className="text-lg font-medium">Subcategoría</h3>
                    <div className="flex mt-2">
                        <input
                            type="text"
                            value={newSubcategory}
                            onChange={(e) => setNewSubcategory(e.target.value)}
                            className="flex-grow border border-gray-300 rounded-l-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <button 
                            onClick={() => handleAddClassification(newSubcategory, setSubcategories, setNewSubcategory)}
                            className="bg-indigo-500 text-white px-4 py-2 rounded-r-md hover:bg-indigo-600"
                        >Agregar</button>
                    </div>
                    <ul className="mt-4">
                        {subcategories.map((value) => (
                        <li key={value} className="flex items-center justify-between border border-gray-300 rounded-md px-3 py-2 mb-2">
                            <span>{value}</span>
                            <button
                            onClick={() => handleRemoveClassification(value, setSubcategories)}
                            className="text-red-500 hover:text-red-700 focus:outline-none"
                            >
                            X
                            </button>
                        </li>
                        ))}
                    </ul>
                </div>
            )}
            {activeSection === "technology" && (
                <div>
                    <h3 className="text-lg font-medium">Tecnología</h3>
                    <div className="flex mt-2">
                        <select
                        value={newTechnology}
                        onChange={e => setNewTechnology(e.target.value)}
                        className="flex-grow border border-gray-300 rounded-l-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        <option value="">Seleccione una tecnología</option>
                        {technology.map(technology => (
                            <option key={technology.id} value={technology.name}>
                            {technology.name}
                            </option>
                        ))}
                        </select>
                        <button
                        onClick={() => handleAddClassification(newTechnology, setTechnologies, setNewTechnology)}
                        className="bg-indigo-500 text-white px-4 py-2 rounded-r-md hover:bg-indigo-600"
                        >
                        Agregar
                        </button>
                    </div>
                    <ul className="mt-4">
                        {technologies.map(value => (
                        <li
                            key={value.id}
                            className="flex items-center justify-between border border-gray-300 rounded-md px-3 py-2 mb-2"
                        >
                            <span>{value.name}</span>
                            <button
                            onClick={() => handleRemoveClassification(value, setTechnologies)}
                            className="text-red-500 hover:text-red-700 focus:outline-none"
                            >
                            X
                            </button>
                        </li>
                        ))}
                    </ul>
                </div>
            )}
            {activeSection === "tag" && (
                <div className="p-4">
                    <h3 className="text-lg font-medium">Tag</h3>
                    <div className="flex mt-2">
                        <input
                            type="text"
                            value={newTag}
                            onChange={(e) => setNewTag(e.target.value)}
                            className="flex-grow border border-gray-300 rounded-l-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        <button 
                            onClick={() => handleAddClassification(newTag, setTags, setNewTag)}
                            className="bg-indigo-500 text-white px-4 py-2 rounded-r-md hover:bg-indigo-600"
                        >
                            Agregar
                        </button>
                    </div>
                    <ul className="mt-4">
                        {tags.map((value) => (
                        <li key={value} className="flex items-center justify-between border border-gray-300 rounded-md px-3 py-2 mb-2">
                            <span>{value}</span>
                            <button
                            onClick={() => handleRemoveClassification(value, setTags)}
                            className="text-red-500 hover:text-red-700 focus:outline-none"
                            >
                            X
                            </button>
                        </li>
                        ))}
                    </ul>
                </div>
            )}
        </div>
    );
}

export { Classification };