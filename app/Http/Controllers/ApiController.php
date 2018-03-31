<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Article;
use App\Portfolio;
use App\Resume;
use App\Category;
use App\Tag;
use App\Testimonial;
use App\Setting;
use App\Http\Resources\Resource;

class ApiController extends Controller
{
    public function __construct(Article $article, Portfolio $portfolio, Resume $resume, Category $category, Tag $tag, Testimonial $testimonial, Setting $setting)
    {
        $this->middleware('auth');
        $this->article = $article;
        $this->portfolio = $portfolio;
        $this->resume = $resume;
        $this->category = $category;
        $this->tag = $tag;
        $this->testimonial = $testimonial;
        $this->setting = $setting;
    }

    public function articleEdit($id)
    {
        $article = $this->article->with('category', 'tag', 'image')->findOrFail($id);

        return new Resource($article);
    }

    public function portfolioEdit($id)
    {
        $portfolio = $this->portfolio->with('category', 'tag', 'image')->findOrFail($id);

        return new Resource($portfolio);
    }

    public function categoryIndex()
    {
        $categories = $this->category->where('type', Input::get('type'))->get();

        return Resource::collection($categories);
    }

    public function categoryStore(Request $request)
    {
        $category = $request->isMethod('put') ? $this->category->findOrFail($request->category_id) : new $this->category;

        $category->name = $request['name'];
        $category->slug = $request['slug'];
        $category->description = $request['description'];
        $category->type = $request['type'];

        if($category->save()){
            return new Resource($category);
        }
    }

    public function categoryDestroy($id)
    {
        $category = $this->category->findOrFail($id);

        if($category->delete()){
            return new Resource($category);
        }
    }

    public function tagIndex()
    {
        $tags = $this->tag->where('type', Input::get('type'))->get();

        return Resource::collection($tags);
    }

    public function tagStore(Request $request)
    {
        $tag = $request->isMethod('put') ? $this->tag->findOrFail($request->tag_id) : new $this->tag;

        $tag->name = $request['name'];
        $tag->slug = $request['slug'];
        $tag->description = $request['description'];
        $tag->type = $request['type'];

        if($tag->save()){
            return new Resource($tag);
        }
    }

    public function tagDestroy($id)
    {
        $tag = $this->tag->findOrFail($id);

        if($tag->delete()){
            return new Resource($tag);
        }
    }

    public function skillIndex()
    {
        $educations = $this->resume->where('resume_type', 'Skill')->orderBy('created_at', 'desc')->get();

        return Resource::collection($educations);
    }

    public function skillStore(Request $request)
    {
        $skill = $request->isMethod('put') ? $this->resume->findOrFail($request->skill_id) : new $this->resume;

        $skill->id = $request['skill_id'];
        $skill->name = $request['name'];
        $skill->institution = $request['institution'];
        $skill->description = $request['description'];
        $skill->resume_type = $request['resume_type'];

        if($skill->save()){
            return new Resource($skill);
        }
    }

    public function skillDestroy($id)
    {
        $skill = $this->resume->findOrFail($id);

        if($skill->delete()){
            return new Resource($skill);
        }
    }

    public function educationIndex()
    {
        $skills = $this->resume->where('resume_type', 'Skill')->orderBy('created_at', 'desc')->get();

        return Resource::collection($skills);
    }

    public function educationStore(Request $request)
    {
        $education = $request->isMethod('put') ? $this->resume->findOrFail($request->education_id) : new $this->resume;

        $education->id = $request['education_id'];
        $education->name = $request['name'];
        $education->institution = $request['institution'];
        $education->date_start = $request['date_start'];
        $education->date_end = $request['date_end'];
        $education->description = $request['description'];
        $education->resume_type = $request['resume_type'];

        if($education->save()){
            return new Resource($education);
        }
    }

    public function educationDestroy($id)
    {
        $education = $this->resume->findOrFail($id);

        if($education->delete()){
            return new Resource($education);
        }
    }

    public function experienceIndex()
    {
        $experiences = $this->resume->where('resume_type', 'Experience')->orderBy('created_at', 'desc')->get();

        return Resource::collection($experiences);
    }

    public function experienceStore(Request $request)
    {
        $experience = $request->isMethod('put') ? $this->resume->findOrFail($request->experience_id) : new $this->resume;

        $experience->id = $request['experience_id'];
        $experience->name = $request['name'];
        $experience->institution = $request['institution'];
        $experience->date_start = $request['date_start'];
        $experience->date_end = $request['date_end'];
        $experience->description = $request['description'];
        $experience->resume_type = $request['resume_type'];

        if($experience->save()){
            return new Resource($experience);
        }
    }

    public function experienceDestroy($id)
    {
        $experience = $this->resume->findOrFail($id);

        if($experience->delete()){
            return new Resource($experience);
        }
    }

    public function testimonialIndex()
    {
        $testimonials = $this->testimonial->all();

        return Resource::collection($testimonials);
    }

    public function testimonialStore(Request $request)
    {
        $testimonial = $request->isMethod('put') ? $this->testimonial->findOrFail($request->testimonial_id) : new $this->testimonial;

        $testimonial->id = $request['testimonial_id'];
        $testimonial->title = $request['title'];
        $testimonial->content = $request['content'];
        $testimonial->author = $request['author'];
        $testimonial->url = $request['url'];

        if($testimonial->save()){
            return new Resource($testimonial);
        }
    }

    public function testimonialDestroy($id)
    {
        $testimonial = $this->testimonial->findOrFail($id);

        if($testimonial->delete()){
            return new Resource($testimonial);
        }
    }
}