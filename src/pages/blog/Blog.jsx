import React, { useState, useEffect } from "react";
import { Routes, Route } from "react-router-dom";
import { Helmet } from "react-helmet";
import { BlogContent } from "./BlogContent";
import { PostBlog } from "./PostBlog";
import Elements from "../../services/Elements";
import SkeletonComponent from "../../components/SkeletonComponent";

function Blog() {
  useEffect(() => {
    window.scrollTo(0, 0);
  });
  const [blogPost, setBlogPost] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const callToAPI = await Elements.obtain("blogposts");
        setBlogPost(callToAPI);
        setIsLoading(false);
      } catch (error) {
        setError(error.message);
        setIsLoading(false);
      }
    };

    fetchData();
  }, []);

  console.log(blogPost);
  return (
    <>
      <Helmet>
        <title>Blog</title>
        <meta
          name="description"
          content="Descubre escritos facinantes en mi blog"
        />
      </Helmet>
      {isLoading ? (
        <SkeletonComponent/>
      ) : (
        <Routes>
          <Route
            path="/"
            element={<BlogContent blogPosts={blogPost}/>}
          />
          <Route
            path="/:link"
            element={<PostBlog blogPosts={blogPost}/>}
          />
        </Routes>
      )}
    </>
  );
}

export default Blog;
