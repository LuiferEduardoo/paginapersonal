const TruncatedHTML = ({ content, maxLength }) => {
    const truncatedContent = content.slice(0, maxLength);
    const shouldShowEllipsis = content.length > maxLength;
    
    return (
      <div className="text-sm text-gray-600">
        <div dangerouslySetInnerHTML={{ __html: truncatedContent }} />
        {shouldShowEllipsis && '...'}
      </div>
    );
}

export default TruncatedHTML;